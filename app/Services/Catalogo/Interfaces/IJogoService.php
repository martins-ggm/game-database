<?php


namespace App\Services\Catalogo\Interfaces;

use app\Http\DTO\Catalogo\JogoDTO;
use App\Models\Catalogo\Jogo;
use Illuminate\Database\Eloquent\Collection;

interface IJogoService
{

    public function criar(JogoDTO $dados): Jogo;
    public function buscarTodos(): Collection;
    public function contarTodos(): int;
    

}
