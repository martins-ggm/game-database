<?php

namespace App\Services\Igdb\Interfaces;

interface IIgdbSincronizacaoService
{
    public function sincronizarLote(int $limite = 500): array;

    public function reiniciarCursor(): void;
}
