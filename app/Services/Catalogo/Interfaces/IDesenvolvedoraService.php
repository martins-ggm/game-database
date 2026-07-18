<?php

namespace App\Services\Catalogo\Interfaces;

use App\Http\DTO\Catalogo\DesenvolvedoraDTO;
use App\Models\Catalogo\Desenvolvedora;

interface IDesenvolvedoraService
{

    public function criar(DesenvolvedoraDTO $dados): Desenvolvedora;
    // public function editar(DesenvolvedoraDTO $dados): Desenvolvedora;
   // public function remover(int $id): void;
}
