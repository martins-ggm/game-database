<?php


namespace App\Services\Gerenciador\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface IPatchNoteService
{

    public function listar(): Collection;
}
