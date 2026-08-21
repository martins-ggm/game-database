<?php

namespace App\Jobs\Igdb;

use App\Models\Catalogo\Jogo;
use App\Services\Imagem\Interfaces\IImagemService;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Baixa a capa do CDN do IGDB, converte para webp e passa a servir do disco
 * local. Disparado na primeira visualização de um jogo que ainda não tem cópia.
 *
 * Enquanto este job não roda, a capa já aparece na tela — vem direto do CDN
 * pelo Jogo::capa(). Por isso ele nunca é urgente e pode falhar sem estragar
 * a experiência.
 */
class BaixarCapaJogo implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    /** Espaço livre mínimo, em bytes, para aceitar gravar mais imagem. */
    private const RESERVA_DISCO = 2 * 1024 * 1024 * 1024;

    public int $tries = 3;

    public array $backoff = [10, 60, 300];


    public function __construct(public int $jogoId)
    {
        $this->onQueue('igdb');
    }


    /** Evita que N visitas simultâneas ao mesmo jogo enfileirem N downloads. */
    public function uniqueId(): string
    {
        return (string) $this->jogoId;
    }


    public function handle(IImagemService $imagemService): void
    {
        $jogo = Jogo::find($this->jogoId);

        if (! $jogo?->precisaBaixarCapa()) {
            return;
        }

        if (! $this->temEspaco()) {
            Log::warning('BaixarCapaJogo abortado: disco de imagens abaixo da reserva.');

            return;
        }

        $resposta = Http::timeout(20)->get($jogo->urlCapaIgdb());

        // 404 no CDN acontece — image_id desatualizado. Não é motivo pra retentar.
        if (! $resposta->successful()) {
            return;
        }

        $caminhos = $imagemService->salvarJogoDeBytes($resposta->body());
        $antigos = array_filter([$jogo->url_imagem_grande, $jogo->url_imagem_pequena]);

        $jogo->url_imagem_grande = $caminhos['grande'];
        $jogo->url_imagem_pequena = $caminhos['pequena'];
        $jogo->save();

        if ($antigos) {
            $imagemService->remover($antigos);
        }
    }


    /**
     * Encher a partição quebraria upload de usuário no meio, com erro obscuro.
     * Só se aplica a disco local — em object storage não há o que medir.
     */
    private function temEspaco(): bool
    {
        $raiz = config('filesystems.disks.' . config('filesystems.imagens_disco') . '.root');

        if (! $raiz || ! is_dir($raiz)) {
            return true;
        }

        return disk_free_space($raiz) > self::RESERVA_DISCO;
    }
}
