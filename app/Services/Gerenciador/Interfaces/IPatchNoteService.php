<?php


namespace App\Services\Gerenciador\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IPatchNoteService
{

    public function listar(): LengthAwarePaginator;
    public function versaoAtual(): ?string;
}
