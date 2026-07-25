<?php

namespace App\Services\Catalogo\Interfaces;

use App\Http\DTO\Catalogo\PlataformaDTO;
use App\Models\Catalogo\Plataforma;
use Illuminate\Database\Eloquent\Collection;

interface IPlataformaService
{


    public function criar(PlataformaDTO $dados): Plataforma;
    public function remover(int $id): void;
    public function editar(PlataformaDTO $dados): Plataforma;
    public function buscarTodas(): Collection;
    public function contarTodas(): int;
    public function buscar(?string $nome = null): Collection;
}
