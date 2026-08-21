<?php

namespace App\Http\DTO\Catalogo;

use Illuminate\Support\Carbon;

/**
 * Traduz um registro de /games do IGDB para o vocabulário do domínio.
 *
 * Diferente do JogoDTO, que valida entrada de formulário: aqui o payload vem de
 * fora e a cobertura é irregular — 87% dos jogos têm summary, 75% têm plataforma.
 * Campo ausente é normal, não erro. Só id e name são inegociáveis.
 */
class JogoIgdbDTO
{
    public function __construct(
        public int $igdbId,
        public string $nome,
        public ?string $slug,
        public ?string $descricao,
        public ?string $lancamento,
        public int $atualizadoEm,
        public ?string $imagemId,
        public ?float $nota,
        public ?int $tipo,
        public array $generos,
        public array $plataformas,
        public array $empresas,
    ) {}


    public static function fromIgdb(array $dados): self
    {
        throw_unless(
            isset($dados['id'], $dados['name']),
            new \Exception('Registro do IGDB sem id ou name: ' . json_encode($dados))
        );

        return new self(
            igdbId: (int) $dados['id'],
            nome: $dados['name'],
            slug: $dados['slug'] ?? null,
            descricao: $dados['summary'] ?? null,
            lancamento: isset($dados['first_release_date'])
                ? Carbon::createFromTimestampUTC($dados['first_release_date'])->format('Y-m-d')
                : null,
            atualizadoEm: (int) ($dados['updated_at'] ?? 0),
            imagemId: $dados['cover']['image_id'] ?? null,
            nota: isset($dados['total_rating']) ? round((float) $dados['total_rating'], 2) : null,
            tipo: isset($dados['game_type']) ? (int) $dados['game_type'] : null,
            generos: self::mapearGeneros($dados),
            plataformas: self::mapearPlataformas($dados),
            empresas: self::mapearEmpresas($dados),
        );
    }


    private static function mapearGeneros(array $dados): array
    {
        return collect($dados['genres'] ?? [])
            ->filter(fn($g) => isset($g['id'], $g['name']))
            ->map(fn($g) => [
                'igdb_id' => (int) $g['id'],
                'nome'    => $g['name'],
                'slug'    => $g['slug'] ?? null,
            ])
            ->values()
            ->all();
    }


    private static function mapearPlataformas(array $dados): array
    {
        return collect($dados['platforms'] ?? [])
            ->filter(fn($p) => isset($p['id'], $p['name']))
            ->map(fn($p) => [
                'igdb_id'    => (int) $p['id'],
                'nome'       => $p['name'],
                'slug'       => $p['slug'] ?? null,
                'abreviacao' => $p['abbreviation'] ?? null,
                'geracao'    => isset($p['generation']) ? (int) $p['generation'] : null,
            ])
            ->values()
            ->all();
    }


    /**
     * involved_companies traz uma linha por (jogo, empresa) com quatro booleanos.
     * A mesma empresa pode aparecer mais de uma vez no payload; nesse caso os
     * papéis são combinados em OR, para caber numa única linha de jogo_empresas.
     */
    private static function mapearEmpresas(array $dados): array
    {
        $porEmpresa = [];

        foreach ($dados['involved_companies'] ?? [] as $vinculo) {
            $empresa = $vinculo['company'] ?? null;

            if (! isset($empresa['id'], $empresa['name'])) {
                continue;
            }

            $id = (int) $empresa['id'];

            $porEmpresa[$id] ??= [
                'igdb_id'        => $id,
                'nome'           => $empresa['name'],
                'slug'           => $empresa['slug'] ?? null,
                'desenvolvedora' => false,
                'publicadora'    => false,
                'portabilidade'  => false,
                'apoio'          => false,
            ];

            $porEmpresa[$id]['desenvolvedora'] = $porEmpresa[$id]['desenvolvedora'] || (bool) ($vinculo['developer'] ?? false);
            $porEmpresa[$id]['publicadora']    = $porEmpresa[$id]['publicadora']    || (bool) ($vinculo['publisher'] ?? false);
            $porEmpresa[$id]['portabilidade']  = $porEmpresa[$id]['portabilidade']  || (bool) ($vinculo['porting'] ?? false);
            $porEmpresa[$id]['apoio']          = $porEmpresa[$id]['apoio']          || (bool) ($vinculo['supporting'] ?? false);
        }

        return array_values($porEmpresa);
    }
}
