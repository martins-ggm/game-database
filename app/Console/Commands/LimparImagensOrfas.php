<?php

namespace App\Console\Commands;

use App\Models\Catalogo\Jogo;
use App\Models\Gerenciador\Usuario;
use App\Services\Imagem\Interfaces\IImagemService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Encontra arquivos de imagem que não são mais referenciados por jogo algum
 * nem por usuário algum.
 *
 * A remoção de jogo é soft delete: a linha continua no banco e pode voltar.
 * Por isso o service não apaga arquivo no remover() — quem apaga é este
 * comando, contando os registros removidos como referência viva.
 *
 * Roda em modo relatório por padrão. Só apaga com --apagar.
 */
class LimparImagensOrfas extends Command
{
    protected $signature = 'imagens:orfas
        {--apagar : Apaga de fato. Sem esta flag o comando só relata}
        {--disco= : Disco a varrer. Por padrão, o que o ImagemService usa}
        {--prefixo=imagens : Pasta raiz da varredura}';

    protected $description = 'Relata (e opcionalmente apaga) imagens que nenhum jogo ou usuário referencia.';


    public function handle(IImagemService $imagemService): int
    {
        $disco = $this->option('disco') ?: config('filesystems.imagens_disco');
        $prefixo = trim($this->option('prefixo'), '/');

        $this->line("Disco: <fg=cyan>{$disco}</>  ·  prefixo: <fg=cyan>{$prefixo}/</>");

        $arquivos = collect(Storage::disk($disco)->allFiles($prefixo));

        if ($arquivos->isEmpty()) {
            $this->info('Nenhum arquivo encontrado — nada a fazer.');

            return self::SUCCESS;
        }

        $referenciados = $this->referenciados();

        // Rede de segurança: banco vazio ou apontando pro lugar errado faria
        // todo arquivo parecer órfão, e o --apagar limparia o disco inteiro.
        if ($referenciados->isEmpty() && $this->option('apagar')) {
            $this->error('O banco não referencia imagem alguma. Isso é suspeito o bastante para não apagar nada.');
            $this->line('Se for mesmo o caso, rode sem --apagar e confira a lista antes.');

            return self::FAILURE;
        }

        $orfas = $arquivos->reject(fn(string $caminho) => $referenciados->has($caminho))->values();

        $this->line(sprintf(
            'Arquivos: %s  ·  referenciados: %s  ·  <fg=yellow>órfãos: %s</>',
            number_format($arquivos->count(), 0, ',', '.'),
            number_format($referenciados->count(), 0, ',', '.'),
            number_format($orfas->count(), 0, ',', '.')
        ));

        if ($orfas->isEmpty()) {
            $this->info('Nenhum órfão. Storage e banco estão de acordo.');

            return self::SUCCESS;
        }

        $bytes = $orfas->sum(fn(string $caminho) => Storage::disk($disco)->size($caminho));

        $this->novaLinhaCom($orfas, $bytes);

        if (! $this->option('apagar')) {
            $this->newLine();
            $this->comment('Modo relatório. Rode com --apagar para remover.');

            return self::SUCCESS;
        }

        // Em fatias: o delete() do Flysystem monta a lista inteira em memória,
        // e no dia do bucket cada fatia vira uma requisição.
        $orfas->chunk(500)->each(fn($fatia) => $imagemService->remover($fatia->all()));

        $this->newLine();
        $this->info(sprintf(
            '%s arquivo(s) removido(s), %s liberados.',
            number_format($orfas->count(), 0, ',', '.'),
            $this->emMegabytes($bytes)
        ));

        return self::SUCCESS;
    }


    /**
     * Caminhos vivos no banco, incluindo os de registros com soft delete —
     * um jogo removido pode ser restaurado e precisa da capa de volta.
     *
     * @return \Illuminate\Support\Collection<string, true>
     */
    private function referenciados(): \Illuminate\Support\Collection
    {
        $caminhos = collect();

        foreach ([Jogo::class, Usuario::class] as $modelo) {
            foreach (['url_imagem_grande', 'url_imagem_pequena'] as $coluna) {
                $modelo::withTrashed()
                    ->whereNotNull($coluna)
                    ->select('id', $coluna)
                    ->orderBy('id') // chunk sem ordem definida repete e pula linhas
                    ->chunk(5000, function ($linhas) use ($caminhos, $coluna) {
                        foreach ($linhas as $linha) {
                            $caminhos[ltrim($linha->{$coluna}, '/')] = true;
                        }
                    });
            }
        }

        return $caminhos;
    }


    private function novaLinhaCom(\Illuminate\Support\Collection $orfas, int $bytes): void
    {
        $this->newLine();
        $this->line("Ocupam <fg=yellow>{$this->emMegabytes($bytes)}</>. Amostra:");

        $orfas->take(10)->each(fn(string $caminho) => $this->line("  {$caminho}"));

        if ($orfas->count() > 10) {
            $this->line('  ... e mais ' . number_format($orfas->count() - 10, 0, ',', '.'));
        }
    }


    private function emMegabytes(int $bytes): string
    {
        return number_format($bytes / 1024 / 1024, 1, ',', '.') . ' MB';
    }
}
