<?php

namespace App\Services\Gerenciador;

use App\Repositorios\Gerenciador\Interfaces\IPatchNoteRepositorio;
use App\Services\Gerenciador\Interfaces\IPatchNoteService;
use Illuminate\Database\Eloquent\Collection;

class PatchNoteService implements IPatchNoteService
{

    public function __construct(protected IPatchNoteRepositorio $patchNoteRepositorio) {}


    public function listar(): Collection
    {
        return $this->patchNoteRepositorio->listar();
    }
}
