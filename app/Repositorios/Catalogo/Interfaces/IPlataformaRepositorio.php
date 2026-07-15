<?php

namespace App\Repositorios\Catalogo\Interfaces;

use App\Models\Catalogo\Plataforma;

interface IPlataformaRepositorio
{



    public function criarNovo(Plataforma $plataforma): Plataforma;
}
