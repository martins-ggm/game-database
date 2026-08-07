<?php

namespace App\Services\Colecao\Interfaces;

use App\Models\Colecao\Colecao;
use Illuminate\Database\Eloquent\Collection;

interface IColecaoService
{

  public function buscarColecao(int $usuarioID): Collection;

  public function adicionarNaColecao(int $jogoID, int $usuarioID, int $situacaoID): Void;
  public function ultimosAdicionados(int $usuarioID, int $quantidade): Collection;
  public function buscarSituacao(int $jogoID, int $usuarioID): ?Colecao;
  public function contarTodasDoUsuario(int $usuarioID): int;
}
