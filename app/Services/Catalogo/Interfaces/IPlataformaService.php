<?php

namespace App\Services\Catalogo\Interfaces;

use App\Http\DTO\Catalogo\PlataformaDTO;
use App\Models\Catalogo\Plataforma;

interface IPlataformaService
{


    public function criar(PlataformaDTO $dados): Plataforma;
    public function remover(int $id): void;
}
