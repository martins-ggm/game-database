<?php


namespace App\Repositorios\Catalogo\Interfaces;

use App\Models\Catalogo\Empresa;
use Illuminate\Database\Eloquent\Collection;


interface IEmpresaRepositorio
{
    public function criarNovo(Empresa $empresa): Empresa;
    public function buscarPorId(int $id): Empresa;
    public function buscarTodas(): Collection;
    public function remover(Empresa $empresa): void;
    public function editar(Empresa $empresa): Empresa;
    public function contarTodas(): int;
    public function buscar(?String $nome = null): Collection;
    public function mapaPorIgdbId(array $igdbIds): array;
    public function criarEmLote(array $registros): void;
}
