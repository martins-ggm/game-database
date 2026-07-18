<?php


namespace App\Repositorios\Catalogo\Interfaces;

use App\Models\Catalogo\Desenvolvedora;
use Illuminate\Support\Collection;

interface IDesenvolvedoraRepositorio {

public function criarNovo(Desenvolvedora $desenvolvedora): Desenvolvedora;

public function buscarTodas(): Collection;



}