<?php

namespace App\Services\Gerenciador;

use App\Repositorios\Gerenciador\Interfaces\IPatchNoteRepositorio;
use App\Services\Gerenciador\Interfaces\IPatchNoteService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PatchNoteService implements IPatchNoteService
{

    public function __construct(protected IPatchNoteRepositorio $patchNoteRepositorio) {}


    public function listar(): LengthAwarePaginator
    {
        return $this->patchNoteRepositorio->listar();
    }

    public function versaoAtual(): ?string
    {
        return $this->patchNoteRepositorio->versaoAtual();
    }
}
