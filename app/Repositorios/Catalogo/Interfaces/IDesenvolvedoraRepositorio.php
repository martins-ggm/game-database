<?php


namespace App\Repositorios\Catalogo\Interfaces;

use App\Models\Catalogo\Desenvolvedora;
use Illuminate\Database\Eloquent\Collection;  


interface IDesenvolvedoraRepositorio {



public function criarNovo(Desenvolvedora $desenvolvedora): Desenvolvedora;
public function buscarPorId(int $id): Desenvolvedora;
public function buscarTodas(): Collection;
public function remover(Desenvolvedora $desenvolvedora): void;
public function editar (Desenvolvedora $desenvolvedora): Desenvolvedora;



}