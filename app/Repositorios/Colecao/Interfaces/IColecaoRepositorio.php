<?php

namespace App\Repositorios\Colecao\Interfaces;

use App\Models\Colecao\Colecao;
use Illuminate\Database\Eloquent\Collection;

interface IColecaoRepositorio
{


    public function buscarColecao(int $id): Collection;
    public function adicionarNaColecao(Colecao $colecao): Void;
}
