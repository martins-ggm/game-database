<?php

namespace App\Repositorios\Catalogo;

use App\Models\Catalogo\Empresa;
use App\Repositorios\Catalogo\Interfaces\IEmpresaRepositorio;
use App\Repositorios\Concerns\ResolvePorIgdbId;
use Illuminate\Database\Eloquent\Collection;


class EmpresaRepositorio implements IEmpresaRepositorio
{
    use ResolvePorIgdbId;


    public function __construct(protected Empresa $modelo) {}



    public function criarNovo(Empresa $empresa): Empresa
    {

        throw_if($this->modelo->newQuery()->where('nome', $empresa->nome)->exists(), new \Exception('Já existe uma Empresa com o nome informado!'));

        $empresa->save();

        return $empresa;
    }



    public function buscarTodas(): Collection
    {

        return $this->modelo->newQuery()->orderBy('nome', 'desc')->get();
    }


    public function buscarPorId(int $id): Empresa
    {

        return $this->modelo->newQuery()->find($id);
    }


    public function remover(Empresa $empresa): void
    {

        $empresa->delete();
    }

    public function editar(Empresa $empresa): Empresa
    {

        throw_if($this->modelo->newQuery()->where('nome', $empresa->nome)->where('id', '!=', $empresa->id)->exists(), new \Exception('Já existe uma empresa com o nome informado'));

        $empresa->save();
        return $empresa;
    }

    public function contarTodas(): int
    {

        return $this->modelo->newQuery()->count();
    }


    public function buscar(?string $nome = null): Collection
    {

        return $this->modelo->newQuery()->when($nome, fn($query) => $query
            ->where('nome', 'ilike', "%{$nome}%"))
            ->orderBy('nome', 'desc')
            ->get();
    }
}
