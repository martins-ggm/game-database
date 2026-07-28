<?php

namespace App\Console\Commands;

use App\Models\Catalogo\Jogo;
use App\Services\Imagem\Interfaces\IImagemService;
use Illuminate\Console\Command;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class EnriquecerJogos extends Command
{
    protected $signature = 'jogos:enriquecer
        {--forcar : Reprocessa mesmo jogos que já têm capa/data}
        {--limite= : Processa no máximo N jogos (útil pra testar)}';

    protected $description = 'Busca capa (Wikipedia) e data de lançamento (Wikidata) dos jogos e salva a capa via ImagemService.';

    // A Wikimedia pede um User-Agent identificável em quem usa a API.
    private string $userAgent = 'GameDB-sandbox/1.0 (projeto de estudo; square.ggm@gmail.com)';

    public function __construct(private IImagemService $imagemService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $query = Jogo::query()->with('desenvolvedora')->orderBy('id');

        // Sem --forcar, só mexe em quem está faltando algo.
        if (! $this->option('forcar')) {
            $query->where(fn ($q) => $q->whereNull('lancamento')->orWhereNull('url_imagem_grande'));
        }

        if ($limite = $this->option('limite')) {
            $query->limit((int) $limite);
        }

        $jogos = $query->get();

        if ($jogos->isEmpty()) {
            $this->info('Nada a fazer — todos os jogos já têm capa e data. (use --forcar pra reprocessar)');
            return self::SUCCESS;
        }

        $total = $jogos->count();
        $this->info("Enriquecendo {$total} jogo(s) via Wikipedia + Wikidata...");
        $this->newLine();

        $comCapa = 0;
        $comData = 0;
        $falhas = 0;

        foreach ($jogos as $i => $jogo) {
            $querImagem = $this->option('forcar') || is_null($jogo->url_imagem_grande);
            $querData   = $this->option('forcar') || is_null($jogo->lancamento);

            $capa = '--';
            $data = '--';

            try {
                $page = $this->buscarPagina($jogo);

                if ($page) {
                    // ---------- capa ----------
                    $arquivo = $page['pageprops']['page_image'] ?? $page['pageprops']['page_image_free'] ?? null;

                    // .svg costuma ser logo (não é capa) e o GD não lê — pula.
                    if ($querImagem && $arquivo && ! str_ends_with(strtolower($arquivo), '.svg')) {
                        $bytes = $this->baixarImagem($arquivo);
                        if ($bytes) {
                            $caminhos = $this->processarImagem($bytes, $arquivo);
                            $jogo->url_imagem_grande  = $caminhos['grande'];
                            $jogo->url_imagem_pequena = $caminhos['pequena'];
                            $capa = 'OK';
                            $comCapa++;
                        }
                    }

                    // ---------- data ----------
                    $qid = $page['pageprops']['wikibase_item'] ?? null;
                    if ($querData && $qid) {
                        $lancamento = $this->buscarData($qid);
                        if ($lancamento) {
                            $jogo->lancamento = $lancamento;
                            $data = $lancamento;
                            $comData++;
                        }
                    }
                }

                $jogo->save();
            } catch (\Throwable $e) {
                $falhas++;
                $this->line(sprintf('[%3d/%d] %-46s ERRO: %s', $i + 1, $total, Str::limit($jogo->nome, 44), $e->getMessage()));
                usleep(200_000);
                continue;
            }

            $this->line(sprintf('[%3d/%d] %-46s capa:%-2s  data:%s', $i + 1, $total, Str::limit($jogo->nome, 44), $capa, $data));

            usleep(200_000); // 200ms entre jogos — educado com a Wikimedia
        }

        $this->newLine();
        $this->info("Concluído: {$comCapa} capa(s) · {$comData} data(s) · {$falhas} falha(s) — de {$total} jogo(s).");

        return self::SUCCESS;
    }

    private function http(): PendingRequest
    {
        return Http::withHeaders(['User-Agent' => $this->userAgent])->timeout(20);
    }

    /**
     * Acha a página do jogo na Wikipedia: 1º tenta o título exato,
     * se cair numa franquia/desambiguação, refaz como busca com dica (dev + "video game").
     */
    private function buscarPagina(Jogo $jogo): ?array
    {
        $base = 'https://en.wikipedia.org/w/api.php';
        $comuns = [
            'action'        => 'query',
            'format'        => 'json',
            'formatversion' => 2,
            'prop'          => 'pageimages|pageprops',
            'piprop'        => 'original',
            'redirects'     => 1,
        ];

        // 1ª tentativa — título exato (seguindo redirects)
        $page = $this->http()->get($base, $comuns + ['titles' => $jogo->nome])->json('query.pages.0');
        if ($this->paginaBoa($page)) {
            return $page;
        }

        // 2ª tentativa — busca com desenvolvedora + "video game" pra desempatar franquias
        $termo = trim($jogo->nome . ' ' . ($jogo->desenvolvedora->nome ?? '') . ' video game');
        $page = $this->http()->get($base, $comuns + [
            'generator' => 'search',
            'gsrsearch' => $termo,
            'gsrlimit'  => 1,
        ])->json('query.pages.0');

        return $this->paginaBoa($page) ? $page : null;
    }

    private function paginaBoa(?array $page): bool
    {
        if (! $page || ($page['missing'] ?? false)) {
            return false;
        }

        $titulo = strtolower($page['title'] ?? '');
        if (str_contains($titulo, 'franchise') || str_contains($titulo, 'disambiguation')) {
            return false;
        }

        return isset($page['pageprops']); // precisa ter ao menos imagem ou QID
    }

    private function baixarImagem(string $arquivo): ?string
    {
        // Special:FilePath resolve o arquivo (livre ou fair-use) seguindo os redirects.
        $url = 'https://en.wikipedia.org/wiki/Special:FilePath/' . rawurlencode($arquivo);
        $resp = $this->http()->get($url);

        return $resp->successful() ? $resp->body() : null;
    }

    /**
     * Ponte bytes → UploadedFile pra passar pelo teu ImagemService (que exige UploadedFile).
     * O modo "test" do UploadedFile aceita um arquivo comum (não veio de upload HTTP real).
     */
    private function processarImagem(string $bytes, string $arquivo): array
    {
        $tmp = tempnam(sys_get_temp_dir(), 'capa_');
        file_put_contents($tmp, $bytes);

        try {
            $uploaded = new UploadedFile($tmp, $arquivo, null, null, true);
            return $this->imagemService->salvarJogo($uploaded);
        } finally {
            @unlink($tmp);
        }
    }

    /**
     * Data de lançamento (P577) do Wikidata, devolvida como "Y-m-d".
     * Regras: descarta datas "deprecated"; prefere precisão de dia (ignora
     * placeholders de ano tipo "2022"); e pega a mais antiga (lançamento original).
     */
    private function buscarData(string $qid): ?string
    {
        $claims = collect($this->http()->get('https://www.wikidata.org/w/api.php', [
            'action' => 'wbgetentities',
            'format' => 'json',
            'ids'    => $qid,
            'props'  => 'claims',
        ])->json("entities.$qid.claims.P577") ?? []);

        // fora as que o próprio Wikidata marcou como incorretas
        $validas = $claims->reject(fn ($c) => ($c['rank'] ?? 'normal') === 'deprecated');

        $datas = $validas
            ->map(fn ($c) => [
                'time'     => $c['mainsnak']['datavalue']['value']['time'] ?? null,
                'precisao' => $c['mainsnak']['datavalue']['value']['precision'] ?? 0,
            ])
            ->filter(fn ($d) => $d['time']);

        // prefere precisão de dia (11); só usa as vagas se não houver nenhuma exata
        $comDia = $datas->where('precisao', '>=', 11);
        $datas = $comDia->isNotEmpty() ? $comDia : $datas;

        return $datas
            ->map(fn ($d) => $this->formatarTime($d['time']))
            ->filter()
            ->sort()
            ->first(); // lançamento original (mais antigo entre as regiões)
    }

    /** "+2022-02-23T00:00:00Z" → "2022-02-23" (mês/dia "00" viram "01"). */
    private function formatarTime(string $time): ?string
    {
        if (! preg_match('/^\+(\d{4})-(\d{2})-(\d{2})/', $time, $m)) {
            return null;
        }
        $mes = $m[2] === '00' ? '01' : $m[2];
        $dia = $m[3] === '00' ? '01' : $m[3];

        return "{$m[1]}-{$mes}-{$dia}";
    }
}
