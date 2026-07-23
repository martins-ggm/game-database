<?php

namespace App\Repositorios\Catalogo;

use App\Models\Catalogo\Plataforma;
use App\Repositorios\Catalogo\Interfaces\IPlataformaRepositorio;
use Illuminate\Database\Eloquent\Collection;


class PlataformaRepositorio implements IPlataformaRepositorio
{

    public function __construct(protected Plataforma $modelo) {}

    public function criarNovo(Plataforma $plataforma): Plataforma
    {

        throw_if($this->modelo->newQuery()->where('nome', $plataforma->nome)->exists(), new \Exception('Já existe uma Plataforma com o nome informado!'));

        $plataforma->save();

        return $plataforma;
    }


    public function buscarPorId(int $id): ?Plataforma
    {
        return $this->modelo->newQuery()->find($id);
    }

    public function remover(Plataforma $plataforma): void
    {

        $plataforma->delete();
    }


    public function editar(Plataforma $plataforma): Plataforma
    {


        throw_if(
            $this->modelo->newQuery()->where('nome', $plataforma->nome)->where('id', '!=', $plataforma->id)->exists(),
            new \Exception('Já Existe uma Plataforma com o nome informado!')
        );

        $plataforma->save();
        return $plataforma;
    }


    public function buscarTodas(): Collection
    {
        return $this->modelo->newQuery()->orderBy('lancamento', 'desc')->get();
    }


    public function contarTodas(): int
    {
        return $this->modelo->newQuery()->count();
    }

    public function buscar(?string $nome = null): Collection
    {
        return $this->modelo->newQuery()
            ->when($nome, fn($query) => $query
                ->where('nome', 'ilike', "%{$nome}%"))
            ->orderBy('nome', 'asc')
            ->get();
    }
}
