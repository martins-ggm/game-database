<?php

namespace App\Repositorios\Catalogo\Interfaces;

use App\Models\Catalogo\Jogo;
use Illuminate\Database\Eloquent\Collection;

Interface IJogoRepositorio {

public function criar(Jogo $jogo, array $plataformas, array $generos): Jogo;
public function buscarTodos(): Collection;
public function contarTodos(): int;




}