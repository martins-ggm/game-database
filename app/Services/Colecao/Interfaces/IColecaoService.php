<?php

namespace App\Services\Colecao\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface IColecaoService {

public function buscarColecao(int $usuarioID): Collection;

public function adicionarNaColecao(int $jogoID, int $usuarioID, int $situacaoID): Void;




}