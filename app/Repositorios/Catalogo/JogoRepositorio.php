<?php

namespace App\Repositorios\Catalogo;

use App\Models\Catalogo\Jogo;
use App\Repositorios\Catalogo\Interfaces\IJogoRepositorio;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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

        return $this->modelo->newQuery()->with(['desenvolvedora', 'plataformas', 'generos'])->find($id);
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




    public function ultimosLancados(int $quantidade): Collection
    {

        return $this->modelo->newQuery()
            ->with(['desenvolvedora', 'generos', 'plataformas'])
            ->whereNotNull('lancamento')
            ->orderBy('lancamento', 'desc')
            ->take($quantidade)
            ->get();
    }

    public function buscaPorNomeSimplificado(string $nome): Collection
    {
        return $this->modelo->newQuery()
            ->select('id', 'nome', 'url_imagem_pequena', 'lancamento')
            ->where('nome', 'ilike', "%{$nome}%")
            ->orderBy('nome')
            ->limit(10)
            ->get();
    }

    public function emAlta(?int $quantidade = null, int $dias = 30, ?int $porPagina = null): Collection|LengthAwarePaginator
    {

        $desde = now()->subDays($dias);

        $query = $this->modelo->newQuery()
            ->withCount(['reviews' => fn($query) => $query->where('created_at', '>=', $desde)])
            ->with(['desenvolvedora', 'generos', 'plataformas'])
            ->orderByDesc('reviews_count')
            ->when($quantidade, fn($query) => $query->take($quantidade));

        return $quantidade ? $query->get() : $query->paginate($porPagina);
    }

    public function buscarPaginado(?string $nome, int $porPagina): LengthAwarePaginator
    {
        return $this->modelo->newQuery()
            ->with(['desenvolvedora', 'plataformas', 'generos'])
            ->when($nome, fn($query) => $query->where('nome', 'ilike', "%{$nome}%"))
            ->orderBy('nome')
            ->paginate($porPagina);
    }
}
