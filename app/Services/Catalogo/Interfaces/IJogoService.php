<?php


namespace App\Services\Catalogo\Interfaces;

use app\Http\DTO\Catalogo\JogoDTO;
use App\Models\Catalogo\Jogo;

interface IJogoService
{

    public function criar(JogoDTO $dados): Jogo;
}
