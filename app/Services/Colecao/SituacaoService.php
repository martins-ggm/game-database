<?php

namespace App\Services\Colecao;

use App\Repositorios\Colecao\Interfaces\ISituacaoRepositorio;
use App\Services\Colecao\Interfaces\ISituacaoService;
use Illuminate\Database\Eloquent\Collection;



class SituacaoService implements ISituacaoService{


public function __construct(protected ISituacaoRepositorio $SituacaoRepositorio) {
}

	public function listarSituacoes(): Collection
    {
    
        return $this->SituacaoRepositorio->listarSituacoes();
    
    }





}