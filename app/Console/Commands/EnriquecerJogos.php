<?php

namespace App\Console\Commands;

use App\Models\Catalogo\Jogo;
use App\Services\Imagem\Interfaces\IImagemService;
use Illuminate\Console\Command;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class EnriquecerJogos extends Command
{
    protected $signature = 'jogos:enriquecer
        {--forcar : Reprocessa mesmo jogos que já têm capa/data/descrição}
        {--limite= : Processa no máximo N jogos (útil pra testar)}';

    protected $description = 'Busca capa, data de lançamento e descrição no Backloggd e salva a capa via ImagemService.';

    // Backloggd é um site (não API): manda um User-Agent de navegador senão bloqueia.
    private string $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Safari/537.36';

    public function __construct(private IImagemService $imagemService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $query = Jogo::query()->orderBy('id');

        // Sem --forcar, só mexe em quem está faltando algo.
        if (! $this->option('forcar')) {
            $query->where(fn ($q) => $q->whereNull('lancamento')
                ->orWhereNull('url_imagem_grande')
                ->orWhereNull('descricao'));
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
        $this->info("Enriquecendo {$total} jogo(s) via Backloggd...");
        $this->newLine();

        $comCapa = 0;
        $comData = 0;
        $comDesc = 0;
        $falhas = 0;

        foreach ($jogos as $i => $jogo) {
            $querImagem    = $this->option('forcar') || is_null($jogo->url_imagem_grande);
            $querData      = $this->option('forcar') || is_null($jogo->lancamento);
            $querDescricao = $this->option('forcar') || is_null($jogo->descricao);

            $capa = '--';
            $data = '--';
            $desc = '--';

            try {
                $html = $this->htmlDoJogo($jogo);

                if ($html) {
                    // ---------- capa ----------
                    if ($querImagem && ($urlCapa = $this->extrairCapa($html))) {
                        $bytes = $this->baixarImagem($urlCapa);
                        if ($bytes) {
                            $caminhos = $this->processarImagem($bytes);
                            $jogo->url_imagem_grande  = $caminhos['grande'];
                            $jogo->url_imagem_pequena = $caminhos['pequena'];
                            $capa = 'OK';
                            $comCapa++;
                        }
                    }

                    // ---------- data ----------
                    if ($querData && ($lancamento = $this->extrairData($html))) {
                        $jogo->lancamento = $lancamento;
                        $data = $lancamento;
                        $comData++;
                    }

                    // ---------- descrição ----------
                    if ($querDescricao && ($descricao = $this->extrairDescricao($html))) {
                        $jogo->descricao = $descricao;
                        $desc = 'OK';
                        $comDesc++;
                    }
                }

                $jogo->save();
            } catch (\Throwable $e) {
                $falhas++;
                $this->line(sprintf('[%3d/%d] %-46s ERRO: %s', $i + 1, $total, Str::limit($jogo->nome, 44), $e->getMessage()));
                usleep(400_000);
                continue;
            }

            $this->line(sprintf('[%3d/%d] %-42s capa:%-2s data:%-10s desc:%s', $i + 1, $total, Str::limit($jogo->nome, 40), $capa, $data, $desc));

            usleep(400_000); // 400ms entre jogos — educado com o Backloggd
        }

        $this->newLine();
        $this->info("Concluído: {$comCapa} capa(s) · {$comData} data(s) · {$comDesc} descrição(ões) · {$falhas} falha(s) — de {$total} jogo(s).");

        return self::SUCCESS;
    }

    private function http(): PendingRequest
    {
        return Http::withHeaders(['User-Agent' => $this->userAgent])->timeout(20);
    }

    /** Baixa o HTML da página do jogo no Backloggd (slug = Str::slug do nome). Null se 404. */
    private function htmlDoJogo(Jogo $jogo): ?string
    {
        $slug = Str::slug($jogo->nome);
        $resp = $this->http()->get("https://backloggd.com/games/{$slug}/");

        return $resp->successful() ? $resp->body() : null;
    }

    /** Capa do og:image (CDN do IGDB), turbinada pra versão 2x (mais nítida). */
    private function extrairCapa(string $html): ?string
    {
        if (! preg_match('/<meta property="og:image" content="([^"]+)"/i', $html, $m)) {
            return null;
        }

        $url = $m[1];

        // só aceita se for capa de verdade (CDN do IGDB), não um placeholder do site
        if (! str_contains($url, 'images.igdb.com')) {
            return null;
        }

        // t_cover_big → t_cover_big_2x (dobro da resolução)
        return str_replace('/t_cover_big/', '/t_cover_big_2x/', $url);
    }

    /**
     * Data de lançamento. Prioriza a data completa ancorada no link "release_year:"
     * (ex: "Sep 09, 2022"); se não achar, cai pro ano do og:title "(2022)".
     */
    private function extrairData(string $html): ?string
    {
        // data completa: <a ... href="...release_year:2022/">Sep 09, 2022</a>
        if (preg_match('#release_year:\d+/">\s*([A-Za-z]+ \d{1,2}, \d{4})#', $html, $m)) {
            try {
                return Carbon::parse($m[1])->format('Y-m-d');
            } catch (\Throwable) {
                // cai pro fallback abaixo
            }
        }

        // fallback: ano do título "Splatoon 3 (2022)"
        if (preg_match('/<meta property="og:title" content="[^"]*\((\d{4})\)"/i', $html, $m)) {
            return $m[1] . '-01-01';
        }

        return null;
    }

    /** Sinopse do jogo (og:description), com entidades HTML decodificadas. */
    private function extrairDescricao(string $html): ?string
    {
        if (! preg_match('/<meta property="og:description" content="([^"]*)"/i', $html, $m)) {
            return null;
        }

        $descricao = trim(html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5, 'UTF-8'));

        return $descricao !== '' ? $descricao : null;
    }

    private function baixarImagem(string $url): ?string
    {
        $resp = $this->http()->get($url);

        return $resp->successful() ? $resp->body() : null;
    }

    /**
     * Ponte bytes → UploadedFile pra passar pelo ImagemService (que exige UploadedFile).
     * O modo "test" aceita um arquivo comum (não veio de upload HTTP real).
     */
    private function processarImagem(string $bytes): array
    {
        $tmp = tempnam(sys_get_temp_dir(), 'capa_');
        file_put_contents($tmp, $bytes);

        try {
            $uploaded = new UploadedFile($tmp, 'capa.jpg', null, null, true);
            return $this->imagemService->salvarJogo($uploaded);
        } finally {
            @unlink($tmp);
        }
    }
}
