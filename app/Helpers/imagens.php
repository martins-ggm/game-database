<?php

use App\Services\Imagem\Interfaces\IImagemService;

if (! function_exists('imagem_url')) {
    /**
     * URL pública de uma imagem armazenada localmente.
     *
     * Existe para que Blade e Resource nunca saibam em que disco o arquivo
     * está. Antes o projeto misturava Storage::url() (disco default) com
     * asset('storage/'...) (caminho cravado) — o segundo ignora o disco e
     * quebra em silêncio no dia da migração.
     */
    function imagem_url(?string $caminho): ?string
    {
        return app(IImagemService::class)->url($caminho);
    }
}
