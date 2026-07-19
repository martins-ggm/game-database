<?php


namespace App\Services\Catalogo\Interfaces;

use App\Http\DTO\Catalogo\GeneroDTO;
use App\Models\Catalogo\Genero;
use Illuminate\Database\Eloquent\Collection;

interface IGeneroService
{

    public function criar(GeneroDTO $dados): Genero;
    public function remover(int $id): void;
    public function editar(GeneroDTO $dados): Genero;
    public function buscarTodos(): Collection;
    public function buscar(?String $nome = null): Collection;
    public function contarTodos(): Int;
}
