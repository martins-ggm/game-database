<?php

namespace App\Repositorios\Catalogo;

use App\Models\Catalogo\Desenvolvedora;
use App\Models\Catalogo\Plataforma;
use App\Repositorios\Catalogo\Interfaces\IDesenvolvedoraRepositorio;
use Illuminate\Database\Eloquent\Collection;
use Override;

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


    public function buscarPorId(int $id): Desenvolvedora
    {

        return $this->modelo->newQuery()->find($id);
    }


    public function remover(Desenvolvedora $desenvolvedora): void
    {

        $desenvolvedora->delete();
    }

    public function editar(Desenvolvedora $desenvolvedora): Desenvolvedora
    {

        throw_if($this->modelo->newQuery()->where('nome', $desenvolvedora->nome)->where('id', '!=', $desenvolvedora->id)->exists(), new \Exception('Já existe uma desenvolvedora com o nome informado'));

        $desenvolvedora->save();
        return $desenvolvedora;
    }
}
