<?php

namespace App\Repositorios\Catalogo;

use App\Models\Catalogo\Jogo;
use App\Repositorios\Catalogo\Interfaces\IJogoRepositorio;
use Illuminate\Database\Eloquent\Collection;

class JogoRepositorio implements IJogoRepositorio
{


    public function __construct(protected Jogo $modelo) {}





    public function criar(Jogo $jogo, array $plataformas, array $generos): Jogo
    {

        throw_if(
            $this->modelo->newQuery()->where('nome', $jogo->nome)->exists(),
            new \Exception('Já existe um jogo com o nome informado')
        );

        $jogo->save();

        $jogo->plataformas()->sync($plataformas);
        $jogo->generos()->sync($generos);

        return $jogo;
    }



    public function buscarTodos(): Collection
    {

        return $this->modelo->newQuery()->get();
    }


    public function contarTodos(): int
    {
        return $this->modelo->newQuery()->count();
    }

    public function buscarPorID(int $id): ?Jogo
    {

        return $this->modelo->newQuery()->find($id);
    }

    public function remover(Jogo $jogo): void
    {
        $jogo->delete();
    }

    public function editar(Jogo $jogo, array $plataformas, array $generos): Jogo
    {


        throw_if($this->modelo->newQuery()
            ->where('nome', $jogo->nome)
            ->where('id', '!=', $jogo->id)
            ->exists(), new \Exception('Já existe um jogo com nome informado.'));

        $jogo->plataformas()->sync($plataformas);
        $jogo->generos()->sync($generos);
        $jogo->save();

        return $jogo;
    }


    public function buscar(?String $nome = null): Collection
    {
        return $this->modelo->newQuery()
            ->when($nome, fn($query) => $query
                ->where('nome', 'ilike', "%{$nome}%"))
            ->orderBy('nome', 'asc')
            ->get();
    }


    public function cadastradosRecentes(): Collection
    {

        return $this->modelo->newQuery()->latest()->take(10)->get();
    }
}
