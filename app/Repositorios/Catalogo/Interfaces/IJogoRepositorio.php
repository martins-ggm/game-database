<?php

namespace App\Repositorios\Catalogo\Interfaces;

use App\Models\Catalogo\Jogo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


interface IJogoRepositorio
{

    public function criar(Jogo $jogo, array $plataformas, array $generos): Jogo;
    public function buscarTodos(): Collection;
    public function contarTodos(): int;
    public function buscarPorID(int $id): ?jogo;
    public function remover(jogo $jogo): void;
    public function editar(jogo $jogo, array $plataformas, array $generos): jogo;
    public function ultimosLancados(int $quantidade): Collection;
    public function buscaPorNomeSimplificado(String $nome): collection;
    public function emAlta(int $quantidade, int $dias): Collection;
    public function buscarPaginado(?string $nome, int $porPagina): LengthAwarePaginator;
}
