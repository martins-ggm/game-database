<?php


namespace App\Services\Catalogo\Interfaces;

use app\Http\DTO\Catalogo\JogoDTO;
use App\Models\Catalogo\Jogo;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IJogoService
{

    public function criar(JogoDTO $dados): Jogo;
    public function buscarTodos(): Collection;
    public function contarTodos(): int;
    public function remover(int $id): void;
    public function editar(JogoDTO $dados): Jogo;
    public function ultimosLancados(int $quantidade): Collection;
    public function buscarPorId(Int $id): ?Jogo;
    public function buscaPorNomeSimplificado(String $nome): Collection;
    public function emAlta(?int $quantidade = null, int $dias = 30, ?int $porPagina = null): Collection|LengthAwarePaginator;
    public function buscarPaginado(?string $nome = null, int $porPagina = 15): LengthAwarePaginator;
}
