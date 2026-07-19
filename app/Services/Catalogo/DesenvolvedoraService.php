<?php

namespace App\Services\Catalogo;

use App\Http\DTO\Catalogo\DesenvolvedoraDTO;
use App\Models\Catalogo\Desenvolvedora;
use App\Repositorios\Catalogo\Interfaces\IDesenvolvedoraRepositorio;
use App\Services\Catalogo\Interfaces\IDesenvolvedoraService;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;


class DesenvolvedoraService implements IDesenvolvedoraService
{


    public function __construct(protected IDesenvolvedoraRepositorio $desenvolvedoraRepositorio,) {}



    public function criar(DesenvolvedoraDTO $dados): Desenvolvedora
    {

        return DB::transaction(function () use ($dados) {

            $desenvolvedora = Desenvolvedora::criar($dados->nome);

            return $this->desenvolvedoraRepositorio->criarNovo($desenvolvedora);
        });
    }


    public function buscarTodas(): Collection
    {
        return $this->desenvolvedoraRepositorio->buscarTodas();
    }

    
    public function remover(int $id): void
    {
        $plataforma = $this->desenvolvedoraRepositorio->buscarPorId($id);
        throw_unless($plataforma, new \Exception('Desenvolvedora não encontrada'));

        DB::transaction(function () use ($plataforma) {

            $this->desenvolvedoraRepositorio->remover($plataforma);

        });

    }

  
    public function editar(DesenvolvedoraDTO $dados): Desenvolvedora
    {
        
            $desenvolvedora = $this->desenvolvedoraRepositorio->buscarPorId($dados->id);
            throw_if(!$desenvolvedora, new \Exception('Desenvolvedora não encontrada'));

          return DB::transaction(function () use ($desenvolvedora, $dados) {

            $desenvolvedora->editar(nome: $dados->nome);

            return $this->desenvolvedoraRepositorio->editar($desenvolvedora);

            });

    }

    public function contarTodas(): int {

        return $this->desenvolvedoraRepositorio->contarTodas();


    }

}
