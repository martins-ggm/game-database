<?php

namespace App\Services\Catalogo;

use App\Http\DTO\Catalogo\JogoDTO;
use App\Models\Catalogo\Jogo;
use App\Repositorios\Catalogo\Interfaces\IJogoRepositorio;
use App\Services\Catalogo\Interfaces\IJogoService;
use App\Services\Imagem\Interfaces\IImagemService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class JogoService implements IJogoService
{


    public function __construct(protected IJogoRepositorio $jogorepositorio, protected IImagemService $imagemService) {}



    public function criar(JogoDTO $dados): Jogo
    {

        $caminhos = $dados->imagem ? $this->imagemService->salvarJogo($dados->imagem) : ['grande' => null, 'pequena' => null];

        try {
            return DB::transaction(function () use ($dados, $caminhos) {

                $jogo = Jogo::criar(
                    nome: $dados->nome,
                    lancamento: $dados->lancamento,
                    grande: $caminhos['grande'],
                    pequena: $caminhos['pequena'],
                    descricao: $dados->descricao
                );

                return $this->jogorepositorio->criar(
                    $jogo,
                    $dados->plataformas,
                    $dados->generos,
                    $dados->desenvolvedora
                );
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
        $caminhosNovos = $dados->imagem ? $this->imagemService->salvarJogo($dados->imagem) : null;

        try {

            $jogoAtualizado = DB::transaction(function () use ($jogo, $dados, $caminhosNovos) {

                $jogo->editar(
                    nome: $dados->nome,
                    lancamento: $dados->lancamento,
                    descricao: $dados->descricao
                );
                if ($caminhosNovos) {
                    $jogo->url_imagem_grande = $caminhosNovos['grande'];
                    $jogo->url_imagem_pequena = $caminhosNovos['pequena'];
                }
                return $this->jogorepositorio->editar(
                    $jogo,
                    $dados->plataformas,
                    $dados->generos,
                    $dados->desenvolvedora
                );
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


    public function ultimosLancados(int $quantidade): Collection
    {

        return $this->jogorepositorio->ultimosLancados(quantidade: $quantidade);
    }


    public function buscarPorId(Int $id): ?Jogo
    {

        $jogo = $this->jogorepositorio->buscarPorID($id);
        throw_unless($jogo, new \Exception('Jogo não encontrado.'));
        return $jogo;
    }


    public function buscaPorNomeSimplificado(string $nome, int $porPagina = 15, int $dias = 30): LengthAwarePaginator
    {
        return $this->jogorepositorio->emAlta(nome: $nome);
    }

    public function emAlta(?int $quantidade = null, int $dias = 30, ?int $porPagina = null, ?string $nome = null): Collection|LengthAwarePaginator
    {

        return $this->jogorepositorio->emAlta(quantidade: $quantidade, dias: $dias, porPagina: $porPagina, nome: $nome);
    }

    public function buscarPaginado(?string $nome = null, int $porPagina = 15): LengthAwarePaginator
    {
        return $this->jogorepositorio->buscarPaginado($nome, $porPagina);
    }

    public function listarCatalogo(?string $nome = null, int $porPagina = 50, int $dias = 15): LengthAwarePaginator
    {
        return $this->jogorepositorio->emAlta(
            dias: $dias,
            porPagina: $porPagina,
            nome: filled($nome) ? trim($nome) : null,
        );
    }
}
