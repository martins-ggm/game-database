<?php

namespace App\Services\Catalogo\Interfaces;

use App\Http\DTO\Catalogo\EmpresaDTO;
use App\Models\Catalogo\Empresa;
use Illuminate\Database\Eloquent\Collection;   


interface IEmpresaService
{

    public function criar(EmpresaDTO $dados): Empresa;
    // public function editar(EmpresaDTO $dados): Empresa;
   public function remover(int $id): void;

   public function buscarTodas(): Collection;

   public function editar(EmpresaDTO $dados): Empresa;
   
   public function contarTodas(): int;

   public function buscar(?String $nome = null): collection;
}
