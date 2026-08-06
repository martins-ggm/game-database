<?php


namespace App\Repositorios\Gerenciador\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IPatchNoteRepositorio
{

    public function listar(): LengthAwarePaginator;
    public function versaoAtual(): ?string;
}
