<?php

namespace App\Services\Catalogo\Interfaces;

use App\Http\DTO\Catalogo\DesenvolvedoraDTO;
use App\Models\Catalogo\Desenvolvedora;
use Illuminate\Database\Eloquent\Collection;   


interface IDesenvolvedoraService
{

    public function criar(DesenvolvedoraDTO $dados): Desenvolvedora;
    // public function editar(DesenvolvedoraDTO $dados): Desenvolvedora;
   // public function remover(int $id): void;

   public function buscarTodas(): Collection;
}
