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



    public function remover(int $id): void
    {

        $genero = $this->generoRepositorio->buscarPorId($id);
        throw_unless($genero, new \Exception('Genero não encontrado'));


        db::transaction(function () use ($genero) {

            $this->generoRepositorio->remover($genero);
        });
    }


    public function editar(GeneroDTO $dados): Genero
    {
       
        $genero = $this->generoRepositorio->buscarPorId($dados->id);

        throw_unless($genero, new \Exception('Genero não encontrado'));

      return DB::transaction(function () use ($genero, $dados) {

            $genero->editar($dados->nome);      
            return $this->generoRepositorio->editar($genero);
        });


    }
}
