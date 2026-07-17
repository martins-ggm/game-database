<?php

namespace App\Services\Catalogo;

use App\Http\DTO\Catalogo\PlataformaDTO;
use App\Models\Catalogo\Plataforma;
use App\Repositorios\Catalogo\Interfaces\IPlataformaRepositorio;
use App\Services\Catalogo\Interfaces\IPlataformaService;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Override;

class PlataformaService implements IPlataformaService
{

    public function __construct(
        protected IPlataformaRepositorio $plataforma_repositorio
    ) {}



    public function criar(PlataformaDTO $dados): Plataforma
    {

        return DB::transaction(function () use ($dados) {

            $plataforma = Plataforma::criar(nome: $dados->nome, lancamento: $dados->lancamento);



            return $this->plataforma_repositorio->criarNovo($plataforma);
        });
    }


    public function remover(int $id): void
    {
        $plataforma = $this->plataforma_repositorio->buscarPorId($id);
        throw_unless($plataforma, new \Exception('Plataforma não encontrada'));
        DB::transaction(function () use ($plataforma) {
            $this->plataforma_repositorio->remover($plataforma);
        });
    }


    public function editar(PlataformaDTO $dados): Plataforma
    {

        $plataforma = $this->plataforma_repositorio->buscarPorId($dados->id);

        throw_unless($plataforma, new \Exception('Plataforma não encontrada'));

        return DB::transaction(function () use ($plataforma, $dados) {
            $plataforma->editar(nome: $dados->nome, lancamento: $dados->lancamento);

            return $this->plataforma_repositorio->editar($plataforma);
        });
    }

    public function buscarTodas(): Collection
    {

        return $this->plataforma_repositorio->buscarTodas();
    }


    
    public function contarTodas(): int
    {
        return $this->plataforma_repositorio->contarTodas();
    }
}
