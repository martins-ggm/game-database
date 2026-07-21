<?php

namespace App\Repositorios\Catalogo\Interfaces;

use App\Models\Catalogo\Jogo;

Interface IJogoRepositorio {

public function criar(Jogo $jogo, array $plataformas, array $generos): Jogo;





}