<?php

namespace App\Repositorios\Catalogo;

use App\Models\Catalogo\Desenvolvedora;
use App\Repositorios\Catalogo\Interfaces\IDesenvolvedoraRepositorio;
use Illuminate\Database\Eloquent\Collection;   
class DesenvolvedoraRepositorio implements IDesenvolvedoraRepositorio
{


    public function __construct(protected Desenvolvedora $modelo) {}



    public function criarNovo(Desenvolvedora $desenvolvedora): Desenvolvedora
    {

        throw_if($this->modelo->newQuery()->where('nome', $desenvolvedora->nome)->exists(), new \Exception('Já existe uma Desenvolvedora com o nome informado!'));

        $desenvolvedora->save();

        return $desenvolvedora;
    }



    public function buscarTodas(): Collection
    {

        return $this->modelo->newQuery()->orderBy('nome', 'desc')->get();
    }
}
