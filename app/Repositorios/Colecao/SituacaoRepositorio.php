<?php

namespace App\Repositorios\Colecao;

use App\Models\Colecao\Situacao;
use App\Repositorios\Colecao\Interfaces\ISituacaoRepositorio;
use Illuminate\Database\Eloquent\Collection;

class SituacaoRepositorio implements ISituacaoRepositorio
{

    public function __construct(protected Situacao $modelo) {}

    public function listarSituacoes(): Collection
    {

        return $this->modelo->newQuery()->get();
    }
}
