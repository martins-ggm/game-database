<?php

namespace App\Services\Catalogo;

use App\Http\DTO\Catalogo\JogoDTO;
use App\Models\Catalogo\Jogo;
use App\Repositorios\Catalogo\Interfaces\IJogoRepositorio;
use App\Services\Catalogo\Interfaces\IJogoService;
use App\Services\Imagem\Interfaces\IImagemService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class JogoService implements IJogoService
{


    public function __construct(protected IJogoRepositorio $jogorepositorio, protected IImagemService $imagemService) {}



    public function criar(JogoDTO $dados): Jogo
    {

        $caminhos = $dados->imagem ? $this->imagemService->salvar($dados->imagem) : ['grande' => null, 'pequena' => null];

        try {
            return DB::transaction(function () use ($dados, $caminhos) {

                $jogo = Jogo::criar(
                    $dados->nome,
                    $dados->desenvolvedora,
                    $caminhos['grande'],
                    $caminhos['pequena']
                );

                return $this->jogorepositorio->criar($jogo, $dados->plataformas, $dados->generos);
            });
        } catch (\Throwable $e) {

            $this->imagemService->remover($caminhos);
            throw $e;
        }
    }



    public function buscarTodos(): Collection
    {

        return $this->jogorepositorio->buscarTodos();
    }


    public function contarTodos(): int
    {

        return $this->jogorepositorio->contarTodos();
    }

    public function remover(int $id): void
    {

        $jogo = $this->jogorepositorio->buscarPorID($id);
        throw_unless($jogo, new \Exception('Jogo não encontrado.'));

        $this->jogorepositorio->remover($jogo);
    }


    public function editar(JogoDTO $dados): Jogo
    {

        $jogo = $this->jogorepositorio->buscarPorID($dados->id);
        throw_unless($jogo, new \Exception('Jogo não encontrado.'));

        $caminhosAntigos = [$jogo->url_imagem_grande, $jogo->url_imagem_pequena];
        $caminhosNovos = $dados->imagem ? $this->imagemService->salvar($dados->imagem) : null;

        try {

            $jogoAtualizado = DB::transaction(function () use ($jogo, $dados, $caminhosNovos) {

                $jogo->editar($dados->nome, $dados->desenvolvedora);
                if ($caminhosNovos) {
                    $jogo->url_imagem_grande = $caminhosNovos['grande'];
                    $jogo->url_imagem_pequena = $caminhosNovos['pequena'];
                }
                return $this->jogorepositorio->editar($jogo, $dados->plataformas, $dados->generos);
            });
        } catch (\Throwable $e) {
            if ($caminhosNovos) {
                $this->imagemService->remover($caminhosNovos);
            }

            throw $e;
        }

        if ($caminhosNovos) {
            $this->imagemService->remover($caminhosAntigos);
        }

        return $jogoAtualizado;
    }

    public function buscar(?String $nome = null): Collection
    {
        return $this->jogorepositorio->buscar($nome);
    }

    public function CadastradosRecentes(): Collection
    {
        
    return $this->jogorepositorio->cadastradosRecentes();

    }
}
