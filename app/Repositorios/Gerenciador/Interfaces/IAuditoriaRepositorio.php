<?php


namespace App\Repositorios\Gerenciador\Interfaces;

use App\Models\Gerenciador\Auditoria;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IAuditoriaRepositorio
{

    public function registrar(Auditoria $auditoria): void;
    public function atividadeRecente(int $quantidade): Collection;
    public function trazerTodas(): LengthAwarePaginator;

}
