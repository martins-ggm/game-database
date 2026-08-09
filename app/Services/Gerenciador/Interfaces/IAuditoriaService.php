<?php


namespace App\Services\Gerenciador\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IAuditoriaService
{

    public function registrar(int $usuarioID,  string $metodo, ?string $rota = null, ?int $alvoID = null): void;
    public function atividadeRecente(int $quantidade): Collection;
    public function trazerTodas(): LengthAwarePaginator;
}
