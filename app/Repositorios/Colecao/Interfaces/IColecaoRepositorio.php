<?php

namespace App\Repositorios\Colecao\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface IColecaoRepositorio  {


public function buscarColecao(int $id): Collection;



}