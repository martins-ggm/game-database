<?php

namespace App\Repositorios\Catalogo;

use App\Models\Catalogo\Genero;
use App\Repositorios\Catalogo\Interfaces\IGeneroRepositorio;
use Override;

class GeneroRepositorio implements IGeneroRepositorio
{


    public function __construct(protected Genero $modelo) {}



    public function criarNovo(Genero $genero): Genero
    {

        throw_if($this->modelo->newQuery()->where('nome', $genero->nome)->exists(), new \Exception('Já existe um gênero com o nome informado'));

        $genero->save();

        return $genero;
    }

    public function buscarPorId(int $id): Genero
    {

        $genero = $this->modelo->newQuery()->find($id);

        return $genero;
    }

    public function remover(Genero $genero): void
    {

        $genero->delete();
    }

  
    public function editar(Genero $genero): Genero
    {
       throw_if($this->modelo->newQuery()->where('nome', $genero->nome)->where('id', '!=', $genero->id)->exists(), new \Exception('Já existe um genero com o nome informado'));

       $genero->save();
       return $genero;
    }

}
