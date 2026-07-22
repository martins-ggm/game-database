<?php

namespace App\Services\Catalogo;

use app\Http\DTO\Catalogo\JogoDTO;
use App\Models\Catalogo\Jogo;
use App\Repositorios\Catalogo\Interfaces\IJogoRepositorio;
use App\Services\Catalogo\Interfaces\IJogoService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
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



    public function buscarTodos(): Collection
    {

        return $this->jogorepositorio->buscarTodos();
    }


    public function contarTodos(): int
    {

        return $this->jogorepositorio->contarTodos();
    }

    public function remover(int $id): void
    {
        $jogo = $this->jogorepositorio->buscarPorID($id);
        throw_unless($jogo, new \Exception('Jogo não encontrado.'));

        $this->jogorepositorio->remover($jogo);
    }


    public function editar(JogoDTO $dados): Jogo
    {
        $jogo = $this->jogorepositorio->buscarPorID($dados->id);
        throw_unless($jogo, new \Exception('Jogo não encontrado.'));

        return DB::transaction(function () use ($jogo, $dados) {

            $jogo->editar($dados->nome, $dados->desenvolvedora);
            return $this->jogorepositorio->editar($jogo, $dados->plataformas, $dados->generos);
        });
    }
}
