<?php

namespace App\Repositorios\Colecao;

use App\Repositorios\Colecao\Interfaces\IColecaoRepositorio;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Colecao\Colecao;

class ColecaoRepositorio implements IColecaoRepositorio
{


    public function __construct(protected Colecao $modelo) {}


    public function buscarColecao(int $usuarioId): Collection
    {

        return $this->modelo->newQuery()->where('usuario_id', $usuarioId)->with(['jogo', 'situacao'])->get();
    }

    public function adicionarNaColecao(Colecao $colecao): Void
    {

        throw_if($this->modelo->newQuery()
            ->where('jogo_id', $colecao->jogo_id)
            ->where('usuario_id', $colecao->usuario_id)
            ->exists(), new \Exception('Jogo já na coleção'));

        $colecao->save();
    }

    public function ultimosAdicionados(int $usuarioID, int $quantidade): Collection
    {
        return $this->modelo->newQuery()
            ->where('usuario_id', $usuarioID)
            ->with(['jogo', 'situacao'])
            ->orderBy('created_at', 'desc')
            ->limit($quantidade)
            ->get();
    }

    public function buscarSituacao(int $jogoID, int $usuarioID): ?Colecao
    {
        return $this->modelo->newQuery()
            ->where('jogo_id', $jogoID)
            ->where('usuario_id', $usuarioID)
            ->with('situacao')
            ->first();
    }

    public function contarTodasDoUsuario(int $usuarioID): int
    {
        return $this->modelo->newQuery()->where('usuario_id', $usuarioID)->count();
    }
}
