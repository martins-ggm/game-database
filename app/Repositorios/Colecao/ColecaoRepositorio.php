<?php

namespace App\Repositorios\Colecao;

use App\Repositorios\Colecao\Interfaces\IColecaoRepositorio;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Catalogo\Colecao;




class ColecaoRepositorio implements IColecaoRepositorio {


public function __construct(protected Colecao $modelo) {
     
    }


	public function buscarColecao(int $usuarioId): Collection
    {
       
            return $this->modelo->newQuery()->where('usuario_id', $usuarioId)->with(['jogo', 'situacao'])->get();


    }





}