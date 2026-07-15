<?php

namespace App\Repositorios\Catalogo;

use App\Models\Catalogo\Plataforma;
use App\Repositorios\Catalogo\Interfaces\IPlataformaRepositorio;

class PlataformaRepositorio implements IPlataformaRepositorio
{

    public function __construct(protected Plataforma $modelo) {}

    public function criarNovo(Plataforma $plataforma): Plataforma
    {

        throw_if($this->modelo->newQuery()->where('nome', $plataforma->nome)->exists(), new \Exception('Já existe uma Plataforma com o nome informado!'));

        $plataforma->save();

        return $plataforma;
    }
}
