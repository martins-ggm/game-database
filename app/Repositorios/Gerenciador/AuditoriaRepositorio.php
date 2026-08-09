<?php

namespace App\Repositorios\Gerenciador;

use App\Models\Gerenciador\Auditoria;
use App\Repositorios\Gerenciador\Interfaces\IAuditoriaRepositorio;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class AuditoriaRepositorio implements IAuditoriaRepositorio
{

    public function __construct(protected Auditoria $modelo) {}



    public function registrar(Auditoria $auditoria): void
    {
        $auditoria->save();
    }

    public function atividadeRecente(int $quantidade): Collection
    {

        return $this->modelo->newQuery()
            ->with('usuario')
            ->where('rota', 'like', 'catalogo.%')
            ->latest()
            ->limit($quantidade)
            ->get();
    }

    public function trazerTodas(): LengthAwarePaginator
    {

        return $this->modelo->newQuery()
            ->with('usuario')
            ->latest()
            ->paginate();
    }
}
