<?php

namespace App\Repositorios\Catalogo\Interfaces;

use App\Models\Catalogo\Plataforma;
use Illuminate\Database\Eloquent\Collection; 

interface IPlataformaRepositorio
{



    public function criarNovo(Plataforma $plataforma): Plataforma;
    public function buscarPorId(int $id): ?Plataforma;
    public function remover (Plataforma $plataforma): void;
    public function editar (Plataforma $plataforma): Plataforma;
    public function buscarTodas(): Collection;
    public function contarTodas(): int;
}
