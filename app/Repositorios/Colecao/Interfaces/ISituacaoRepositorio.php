<?php

namespace App\Repositorios\Colecao\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface ISituacaoRepositorio
{

    public function listarSituacoes(): Collection;
}
