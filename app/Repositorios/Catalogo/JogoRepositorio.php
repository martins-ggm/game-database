<?php

namespace App\Repositorios\Catalogo;

use App\Models\Catalogo\Jogo;
use App\Repositorios\Catalogo\Interfaces\IJogoRepositorio;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class JogoRepositorio implements IJogoRepositorio
{


    public function __construct(protected Jogo $modelo) {}





    public function criar(Jogo $jogo, array $plataformas, array $generos, ?int $desenvolvedora = null): Jogo
    {

        throw_if(
            $this->modelo->newQuery()->where('nome', $jogo->nome)->exists(),
            new \Exception('Já existe um jogo com o nome informado')
        );

        $jogo->save();

        $jogo->plataformas()->sync($plataformas);
        $jogo->generos()->sync($generos);
        $this->definirDesenvolvedora($jogo, $desenvolvedora);

        return $jogo;
    }


    /**
     * O papel de desenvolvedora vive no pivot jogo_empresas. Aqui só existe o
     * caminho manual (uma empresa por jogo, vinda do formulário) — o sync do
     * IGDB grava os quatro papéis por conta própria.
     */
    private function definirDesenvolvedora(Jogo $jogo, ?int $empresaId): void
    {
        $vinculosAtuais = $jogo->empresas()->pluck('empresas.id')->all();

        if ($vinculosAtuais) {
            $jogo->empresas()->updateExistingPivot($vinculosAtuais, ['desenvolvedora' => false]);
        }

        if ($empresaId) {
            $jogo->empresas()->syncWithoutDetaching([$empresaId => ['desenvolvedora' => true]]);
        }

        // Vínculo que ficou sem papel nenhum não representa mais nada.
        $jogo->empresas()->newPivotStatement()
            ->where('jogo_id', $jogo->id)
            ->where('desenvolvedora', false)
            ->where('publicadora', false)
            ->where('portabilidade', false)
            ->where('apoio', false)
            ->delete();

        $jogo->unsetRelation('empresas')->unsetRelation('desenvolvedoras');
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

        return $this->modelo->newQuery()->with(['desenvolvedoras', 'plataformas', 'generos'])->find($id);
    }

    public function remover(Jogo $jogo): void
    {
        $jogo->delete();
    }

    public function editar(Jogo $jogo, array $plataformas, array $generos, ?int $desenvolvedora = null): Jogo
    {


        throw_if($this->modelo->newQuery()
            ->where('nome', $jogo->nome)
            ->where('id', '!=', $jogo->id)
            ->exists(), new \Exception('Já existe um jogo com nome informado.'));

        $jogo->plataformas()->sync($plataformas);
        $jogo->generos()->sync($generos);
        $jogo->save();
        $this->definirDesenvolvedora($jogo, $desenvolvedora);

        return $jogo;
    }




    public function ultimosLancados(int $quantidade): Collection
    {

        return $this->modelo->newQuery()
            ->with(['desenvolvedoras', 'generos', 'plataformas'])
            ->whereNotNull('lancamento')
            // O IGDB cadastra jogo com data futura (há um marcado pra 2030).
            // Sem este corte a home anuncia como "último lançamento" algo que
            // ainda não saiu.
            ->where('lancamento', '<=', now())
            ->orderBy('lancamento', 'desc')
            ->take($quantidade)
            ->get();
    }


    public function emAlta(?int $quantidade = null, int $dias = 30, ?int $porPagina = null, ?string $nome = null): Collection|LengthAwarePaginator
    {

        $desde = now()->subDays($dias);

        $query = $this->modelo->newQuery()
            ->withCount(['reviews' => fn($query) => $query->where('created_at', '>=', $desde)])
            ->with(['desenvolvedoras', 'generos', 'plataformas'])
            ->when($nome, fn($query) => $query->where('nome', 'ilike', "%{$nome}%"))
            ->orderByDesc('reviews_count')
            ->orderBy('nome')
            ->orderBy('id')
            ->when($quantidade, fn($query) => $query->take($quantidade));

        return $quantidade ? $query->get() : $query->paginate($porPagina);
    }

    public function buscarPaginado(?string $nome, int $porPagina): LengthAwarePaginator
    {
        return $this->modelo->newQuery()
            ->with(['desenvolvedoras', 'plataformas', 'generos'])
            ->when($nome, fn($query) => $query->where('nome', 'ilike', "%{$nome}%"))
            ->orderBy('nome')
            ->paginate($porPagina);
    }

    /**
     * Carga em lote para o sync. Sem isto, o laço de 500 jogos faria 500
     * selects por lote — 311 mil no backfill inteiro.
     *
     * @return Collection<int, Jogo>  indexada por igdb_id
     */
    public function porIgdbIds(array $igdbIds): Collection
    {
        if ($igdbIds === []) {
            return new Collection();
        }

        return $this->modelo->newQuery()
            ->withTrashed()
            ->whereIn('igdb_id', $igdbIds)
            ->get()
            ->keyBy('igdb_id');
    }

    public function buscarPorIgdbId(int $igdbId): ?Jogo
    {
        return $this->modelo->newQuery()
            ->withTrashed()
            ->where('igdb_id', $igdbId)
            ->first();
    }

    public function salvarSincronizado(Jogo $jogo, array $plataformas, array $generos, array $empresas): Jogo
    {
        $jogo->save();

        $jogo->plataformas()->sync($plataformas);
        $jogo->generos()->sync($generos);
        $jogo->empresas()->sync($empresas);

        return $jogo;
    }
}
