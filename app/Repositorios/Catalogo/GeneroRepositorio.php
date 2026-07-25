<?php

namespace App\Repositorios\Catalogo;

use App\Models\Catalogo\Genero;
use App\Repositorios\Catalogo\Interfaces\IGeneroRepositorio;
use Illuminate\Database\Eloquent\Collection;

class GeneroRepositorio implements IGeneroRepositorio
{


    public function __construct(protected Genero $modelo) {}



    public function criarNovo(Genero $genero): Genero
    {

        throw_if(
            $this->modelo->newQuery()->where('nome', $genero->nome)->exists(),
            new \Exception('Já existe um gênero com o nome informado')
        );

        $genero->save();

        return $genero;
    }

    public function buscarPorId(int $id): ?Genero
    {

        $genero = $this->modelo->newQuery()->find($id);

        return $genero;
    }

    public function remover(Genero $genero): void
    {

        $genero->delete();
    }


    public function editar(Genero $genero): Genero
    {
        throw_if($this->modelo->newQuery()->where('nome', $genero->nome)->where('id', '!=', $genero->id)->exists(), new \Exception('Já existe um genero com o nome informado'));

        $genero->save();
        return $genero;
    }

    public function buscarTodos(): Collection
    {

        return $this->modelo->newQuery()->get();
    }


    public function buscar(?string $nome = null): Collection
    {
        return $this->modelo->newQuery()
            ->when($nome, fn($query) => $query->where('nome', 'ilike', "%{$nome}%"))
            ->orderBy('nome', 'asc')
            ->get();
    }



    public function contarTodos(): Int
    {
        return $this->modelo->newQuery()->count();
    }
}
