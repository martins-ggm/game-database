<?php


namespace App\Repositorios\Catalogo\Interfaces;

use App\Models\Catalogo\Genero;
use Illuminate\Database\Eloquent\Collection;

interface IGeneroRepositorio
{

    public function criarNovo(Genero $Genero): Genero;
    public function buscarPorId(int $id): ?Genero;
    public function remover(Genero $Genero): void;
    public function editar(Genero $genero): Genero;
    public function buscarTodos(): Collection;
    public function buscar(?String $nome = null): Collection;
    public function contarTodos(): int;
}
