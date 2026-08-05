<?php


namespace App\Repositorios\Gerenciador\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface IPatchNoteRepositorio
{

    public function listar(): Collection;
}
