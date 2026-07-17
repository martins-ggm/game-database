<?php

namespace App\Repositorios\Catalogo\Interfaces;

use App\Models\Catalogo\Plataforma;

interface IPlataformaRepositorio
{



    public function criarNovo(Plataforma $plataforma): Plataforma;
    public function buscarPorId(int $id): ?Plataforma;
    public function remover (Plataforma $plataforma): void;
    public function editar (Plataforma $plataforma): Plataforma;
}
