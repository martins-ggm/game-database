<?php

namespace App\Repositorios\Concerns;

/**
 * Resolução em lote de entidades vindas do IGDB.
 *
 * Um lote de 500 jogos traz ~151 entidades distintas (99 empresas, 37
 * plataformas, 15 gêneros — medido). Resolvendo uma a uma, são 151 consultas
 * por lote; em 622 lotes, 94 mil idas ao banco. Em lote, ~3.700.
 *
 * Exige a propriedade $modelo, presente em todos os repositórios do projeto.
 */
trait ResolvePorIgdbId
{
    /**
     * @param  array<int>  $igdbIds
     * @return array<int, int>  igdb_id => id local
     */
    public function mapaPorIgdbId(array $igdbIds): array
    {
        if ($igdbIds === []) {
            return [];
        }

        return $this->modelo->newQuery()
            ->withTrashed()
            ->whereIn('igdb_id', $igdbIds)
            ->pluck('id', 'igdb_id')
            ->all();
    }


    /** @param  array<int, array<string, mixed>>  $registros */
    public function criarEmLote(array $registros): void
    {
        if ($registros === []) {
            return;
        }

        $agora = now();

        $this->modelo->newQuery()->insertOrIgnore(
            array_map(
                fn(array $registro) => $registro + ['created_at' => $agora, 'updated_at' => $agora],
                $registros
            )
        );
    }
}
