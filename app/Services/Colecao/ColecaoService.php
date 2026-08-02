<?php


namespace App\Services\Colecao;

use App\Models\Colecao\Colecao;

use App\Repositorios\Catalogo\Interfaces\IJogoRepositorio;
use App\Repositorios\Colecao\Interfaces\IColecaoRepositorio;
use App\Repositorios\Gerenciador\Interfaces\IUsuarioRepositorio;
use App\Services\Colecao\Interfaces\IColecaoService;
use Illuminate\Database\Eloquent\Collection;


class ColecaoService implements IColecaoService
{

    public function __construct(
        protected IColecaoRepositorio $colecaoRepositorio,
        protected IJogoRepositorio $jogoRepositorio,
        protected IUsuarioRepositorio $usuarioRepositorio
    ) {}



    public function buscarColecao(int $usuarioID): Collection
    {
        $colecao = $this->colecaoRepositorio->buscarColecao($usuarioID);
        throw_unless($colecao, new \Exception('Coleção não encontrada'));

        return $colecao;
    }

    public function adicionarNaColecao(int $jogoID, int $usuarioID, int $situacaoID): Void
    {
        throw_unless($this->jogoRepositorio->buscarPorID($jogoID), new \Exception('Jogo não encontrado'));
        throw_unless($this->usuarioRepositorio->buscarPorID($usuarioID), new \Exception('Usuário não encontrado'));

        $colecao = Colecao::criar($jogoID, $usuarioID, $situacaoID);

        $this->colecaoRepositorio->adicionarNaColecao($colecao);
    }

    public function ultimosAdicionados(int $usuarioID, int $quantidade): Collection
    {

        return $this->colecaoRepositorio->ultimosAdicionados($usuarioID, $quantidade);
    }

    public function buscarSituacao(int $jogoID, int $usuarioID): ?Colecao
    {

        return $this->colecaoRepositorio->buscarSituacao($jogoID, $usuarioID);
    }
}
