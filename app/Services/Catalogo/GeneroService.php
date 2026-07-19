<?php


namespace App\Services\Catalogo;

use App\Http\DTO\Catalogo\GeneroDTO;
use App\Models\Catalogo\Genero;
use App\Repositorios\Catalogo\Interfaces\IGeneroRepositorio;
use App\Services\Catalogo\Interfaces\IGeneroService;
use Illuminate\Support\Facades\DB;




class GeneroService implements IGeneroService
{

    public  function __construct(protected IGeneroRepositorio $generoRepositorio) {}


    public function criar(GeneroDTO $dados): Genero
    {

        return DB::transaction(function () use ($dados) {

            $genero = Genero::criar($dados->nome);
            return $this->generoRepositorio->criarNovo($genero);
        });
    }
}
