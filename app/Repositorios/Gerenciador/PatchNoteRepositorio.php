<?php

namespace App\Repositorios\Gerenciador;

use App\Models\Gerenciador\PatchNote;
use App\Repositorios\Gerenciador\Interfaces\IPatchNoteRepositorio;
use Illuminate\Database\Eloquent\Collection;

class PatchNoteRepositorio implements IPatchNoteRepositorio
{

    public function __construct(protected PatchNote $modelo) {}


    public function listar(): Collection
    {
        return $this->modelo->newQuery()
            ->orderByDesc('lancado_em')
            ->get();
    }
}
