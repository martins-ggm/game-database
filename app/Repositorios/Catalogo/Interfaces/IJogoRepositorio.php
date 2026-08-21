<?php

namespace App\Repositorios\Catalogo\Interfaces;

use App\Models\Catalogo\Jogo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


interface IJogoRepositorio
{

    public function criar(Jogo $jogo, array $plataformas, array $generos, ?int $desenvolvedora = null): Jogo;
    public function buscarTodos(): Collection;
    public function contarTodos(): int;
    public function buscarPorID(int $id): ?jogo;
    public function remover(jogo $jogo): void;
    public function editar(Jogo $jogo, array $plataformas, array $generos, ?int $desenvolvedora = null): Jogo;
    public function ultimosLancados(int $quantidade): Collection;
    public function buscaPorNomeSimplificado(String $nome): collection;
    public function emAlta(?int $quantidade = null, int $dias = 30, ?int $porPagina = null): Collection|LengthAwarePaginator;
    public function buscarPaginado(?string $nome, int $porPagina): LengthAwarePaginator;
    public function porIgdbIds(array $igdbIds): Collection;
    public function buscarPorIgdbId(int $igdbId): ?Jogo;
    public function salvarSincronizado(Jogo $jogo, array $plataformas, array $generos, array $empresas): Jogo;
}
