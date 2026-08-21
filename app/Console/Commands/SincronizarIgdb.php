<?php

namespace App\Console\Commands;

use App\Services\Igdb\Interfaces\IIgdbSincronizacaoService;
use Illuminate\Console\Command;

class SincronizarIgdb extends Command
{
    protected $signature = 'igdb:sincronizar
        {--lotes=1 : Quantos lotes processar nesta execução (use --tudo para ir até o fim)}
        {--limite=500 : Registros por lote (máximo aceito pelo IGDB)}
        {--tudo : Percorre o catálogo inteiro, lote após lote, até acabar}
        {--reiniciar : Zera o cursor antes de começar — reprocessa tudo do início}';

    protected $description = 'Sincroniza os metadados dos jogos do IGDB para o banco local. Não baixa imagem alguma.';


    public function __construct(private IIgdbSincronizacaoService $sincronizacao)
    {
        parent::__construct();
    }


    public function handle(): int
    {
        if ($this->option('reiniciar')) {
            $this->sincronizacao->reiniciarCursor();
            $this->warn('Cursor zerado — o catálogo será percorrido desde o início.');
        }

        $limite = (int) $this->option('limite');
        $lotes = $this->option('tudo') ? PHP_INT_MAX : (int) $this->option('lotes');

        $totalCriados = 0;
        $totalAtualizados = 0;
        $inicio = microtime(true);

        for ($n = 1; $n <= $lotes; $n++) {
            try {
                $r = $this->sincronizacao->sincronizarLote($limite);
            } catch (\Throwable $e) {
                // O cursor só avança em lote concluído, então basta rodar de novo.
                $this->newLine();
                $this->error("Lote {$n} falhou: {$e->getMessage()}");
                $this->line('O cursor não avançou. Rode o comando novamente para retomar daqui.');

                return self::FAILURE;
            }

            $totalCriados += $r['criados'];
            $totalAtualizados += $r['atualizados'];

            $this->line(sprintf(
                '[%4d] %4d processados  ·  %4d novos  ·  %4d atualizados  ·  cursor %d (%s)',
                $n,
                $r['processados'],
                $r['criados'],
                $r['atualizados'],
                $r['cursor'],
                date('Y-m-d', $r['cursor'])
            ));

            if ($r['concluido']) {
                $this->newLine();
                $this->info('Fim do catálogo — não há mais registros após o cursor.');
                break;
            }
        }

        $segundos = round(microtime(true) - $inicio);

        $this->newLine();
        $this->info(sprintf(
            'Concluído em %ds: %s novo(s), %s atualizado(s).',
            $segundos,
            number_format($totalCriados, 0, ',', '.'),
            number_format($totalAtualizados, 0, ',', '.')
        ));
        $this->line('Capas não foram baixadas — elas vêm sob demanda.');

        return self::SUCCESS;
    }
}
