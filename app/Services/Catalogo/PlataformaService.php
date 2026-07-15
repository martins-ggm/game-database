<?php

namespace App\Services\Catalogo;

use App\Http\DTO\Catalogo\PlataformaDTO;
use App\Models\Catalogo\Plataforma;
use App\Repositorios\Catalogo\Interfaces\IPlataformaRepositorio;
use App\Services\Catalogo\Interfaces\IPlataformaService;
use Illuminate\Support\Facades\DB;

class PlataformaService implements IPlataformaService
{

    public function __construct(
        protected IPlataformaRepositorio $plataforma_repositorio
    ) {}



    public function criar(PlataformaDTO $dados): Plataforma
    {

        return DB::transaction(function () use ($dados) {

            $plataforma = Plataforma::criar(nome: $dados->nome);



            return $this->plataforma_repositorio->criarNovo($plataforma);
        });
    }
}
