<?php

namespace App\Repositorios\Gerenciador;

use App\Models\Gerenciador\PatchNote;
use App\Repositorios\Gerenciador\Interfaces\IPatchNoteRepositorio;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PatchNoteRepositorio implements IPatchNoteRepositorio
{

    public function __construct(protected PatchNote $modelo) {}


    public function listar(): LengthAwarePaginator
    {
        return $this->modelo->newQuery()
            ->orderByDesc('lancado_em')
            ->orderByDesc('id') // desempate estável quando duas versões têm a mesma data
            ->paginate(3);
    }

    public function versaoAtual(): ?string
    {
        return $this->modelo->newQuery()
            ->orderByDesc('lancado_em')
            ->orderByDesc('id') // garante a versão inserida mais recentemente no empate de data
            ->value('versao');
    }
}
