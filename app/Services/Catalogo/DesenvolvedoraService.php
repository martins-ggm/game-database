<?php

namespace App\Services\Catalogo;

use App\Http\DTO\Catalogo\DesenvolvedoraDTO;
use App\Models\Catalogo\Desenvolvedora;
use App\Repositorios\Catalogo\Interfaces\IDesenvolvedoraRepositorio;
use App\Services\Catalogo\Interfaces\IDesenvolvedoraService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

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
}
