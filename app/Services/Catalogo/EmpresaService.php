<?php

namespace App\Services\Catalogo;

use App\Http\DTO\Catalogo\EmpresaDTO;
use App\Models\Catalogo\Empresa;
use App\Repositorios\Catalogo\Interfaces\IEmpresaRepositorio;
use App\Services\Catalogo\Interfaces\IEmpresaService;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;


class EmpresaService implements IEmpresaService
{


    public function __construct(protected IEmpresaRepositorio $empresaRepositorio,) {}



    public function criar(EmpresaDTO $dados): Empresa
    {

        return DB::transaction(function () use ($dados) {

            $empresa = Empresa::criar($dados->nome);

            return $this->empresaRepositorio->criarNovo($empresa);
        });
    }


    public function buscarTodas(): Collection
    {
        return $this->empresaRepositorio->buscarTodas();
    }


    public function remover(int $id): void
    {
        $plataforma = $this->empresaRepositorio->buscarPorId($id);
        throw_unless($plataforma, new \Exception('Empresa não encontrada'));

        DB::transaction(function () use ($plataforma) {
            throw_if($plataforma->jogos()->exists(), new \Exception('Existem jogos vinculados a empresa selecionada.'));

            $this->empresaRepositorio->remover($plataforma);
        });
    }


    public function editar(EmpresaDTO $dados): Empresa
    {

        $empresa = $this->empresaRepositorio->buscarPorId($dados->id);
        throw_if(!$empresa, new \Exception('Empresa não encontrada'));

        return DB::transaction(function () use ($empresa, $dados) {

            $empresa->editar(nome: $dados->nome);

            return $this->empresaRepositorio->editar($empresa);
        });
    }

    public function contarTodas(): int
    {

        return $this->empresaRepositorio->contarTodas();
    }

    public function buscar(?string $nome = null): Collection
    {
        return $this->empresaRepositorio->buscar($nome);
    }
}
