<?php

namespace App\Repositorios\Catalogo;

use App\Models\Catalogo\Genero;
use App\Repositorios\Catalogo\Interfaces\IGeneroRepositorio;
use Exception;
use Override;

class GeneroRepositorio implements IGeneroRepositorio {


public function __construct(protected Genero $modelo) {}



	public function criarNovo(Genero $genero): Genero
    {
      
        throw_if($this->modelo->newQuery()->where('nome', $genero->nome)->exists(), new \Exception('Já existe um gênero com o nome informado'));

        $genero->save();

        return $genero;

    }






}