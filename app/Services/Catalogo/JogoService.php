<?php

namespace App\Services\Catalogo;

use app\Http\DTO\Catalogo\JogoDTO;
use App\Models\Catalogo\Jogo;
use App\Repositorios\Catalogo\Interfaces\IJogoRepositorio;
use App\Services\Catalogo\Interfaces\IJogoService;
use Illuminate\Support\Facades\DB;



class JogoService implements IJogoService
{


    public function __construct(protected IJogoRepositorio $jogorepositorio) {}



    public function criar(JogoDTO $dados): Jogo
    {

        return DB::transaction(function () use ($dados) {

            $jogo = Jogo::criar($dados->nome, $dados->desenvolvedora);
            return $this->jogorepositorio->criar($jogo, $dados->plataformas, $dados->generos);
        });
    }
}
