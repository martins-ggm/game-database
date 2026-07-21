<?php

namespace App\Repositorios\Catalogo;

use App\Models\Catalogo\Jogo;
use App\Repositorios\Catalogo\Interfaces\IJogoRepositorio;


class JogoRepositorio implements IJogoRepositorio
{


    public function __construct(protected Jogo $modelo) {}





    public function criar(Jogo $jogo, array $plataformas, array $generos): Jogo
    {

        throw_if(
            $this->modelo->newQuery()->where('nome', $jogo->nome)->exists(),
            new \Exception('Já existe um jogo com o nome informado')
        );

        $jogo->save();

        $jogo->plataformas()->sync($plataformas);
        $jogo->generos()->sync($generos);

        return $jogo;
    }
}
