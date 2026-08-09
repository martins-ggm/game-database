<?php

namespace App\Services\Gerenciador;

use App\Models\Gerenciador\Auditoria;
use App\Repositorios\Gerenciador\Interfaces\IAuditoriaRepositorio;
use App\Services\Gerenciador\Interfaces\IAuditoriaService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Override;

class AuditoriaService implements IAuditoriaService
{

    public function __construct(protected IAuditoriaRepositorio $auditoriaRepositorio) {}


    public function registrar(int $usuarioID,  string $metodo, ?string $rota = null, ?int $alvoID = null): void
    {

        $auditoria = Auditoria::criar(
            usuarioId: $usuarioID,
            rota: $rota,
            metodo: $metodo,
            alvoId: $alvoID
        );

        $this->auditoriaRepositorio->registrar($auditoria);
    }

    public function atividadeRecente(int $quantidade): Collection
    {
        return $this->auditoriaRepositorio->atividadeRecente($quantidade);
    }

    public function trazerTodas(): LengthAwarePaginator
    {
        return $this->auditoriaRepositorio->trazerTodas();
    }
}
