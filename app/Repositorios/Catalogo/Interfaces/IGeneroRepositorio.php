<?php


namespace App\Repositorios\Catalogo\Interfaces;

use App\Models\Catalogo\Genero;

interface IGeneroRepositorio{

public function criarNovo(Genero $Genero): Genero;





}