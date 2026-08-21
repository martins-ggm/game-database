<?php

namespace App\Services\Igdb;

use App\Http\DTO\Catalogo\JogoIgdbDTO;
use App\Models\Catalogo\Jogo;
use App\Models\Igdb\IgdbSincronizacao;
use App\Repositorios\Catalogo\Interfaces\IEmpresaRepositorio;
use App\Repositorios\Catalogo\Interfaces\IGeneroRepositorio;
use App\Repositorios\Catalogo\Interfaces\IJogoRepositorio;
use App\Repositorios\Catalogo\Interfaces\IPlataformaRepositorio;
use App\Services\Igdb\Interfaces\IIgdbClient;
use App\Services\Igdb\Interfaces\IIgdbSincronizacaoService;
use Illuminate\Support\Facades\DB;

class IgdbSincronizacaoService implements IIgdbSincronizacaoService
{
    private const ENTIDADE = 'games';

    private const CAMPOS = 'id, name, slug, summary, first_release_date, updated_at, game_type, total_rating,
        cover.image_id,
        genres.id, genres.name, genres.slug,
        platforms.id, platforms.name, platforms.slug, platforms.abbreviation, platforms.generation,
        involved_companies.company.id, involved_companies.company.name, involved_companies.company.slug,
        involved_companies.developer, involved_companies.publisher,
        involved_companies.porting, involved_companies.supporting';


    public function __construct(
        protected IIgdbClient $igdb,
        protected IJogoRepositorio $jogoRepositorio,
        protected IEmpresaRepositorio $empresaRepositorio,
        protected IPlataformaRepositorio $plataformaRepositorio,
        protected IGeneroRepositorio $generoRepositorio,
    ) {}


    public function sincronizarLote(int $limite = 500): array
    {
        $sincronizacao = $this->cursor();
        $cursor = $sincronizacao->ultimo_updated_at;

        $dtos = $this->buscarLote($cursor, $limite);

        if ($dtos === []) {
            return $this->resultado(0, 0, $cursor, true);
        }

        $mapas = $this->resolverEntidades($dtos);
        $existentes = $this->jogoRepositorio->porIgdbIds(
            array_map(fn(JogoIgdbDTO $dto) => $dto->igdbId, $dtos)
        );

        $criados = 0;
        $atualizados = 0;

        DB::transaction(function () use ($dtos, $mapas, $existentes, &$criados, &$atualizados) {
            foreach ($dtos as $dto) {
                $this->persistir($dto, $mapas, $existentes->get($dto->igdbId)) ? $criados++ : $atualizados++;
            }
        });

        $novoCursor = max(array_map(fn(JogoIgdbDTO $dto) => $dto->atualizadoEm, $dtos));

        // Guarda contra travamento: só ocorreria se um único timestamp tivesse
        // mais registros que o limite do lote. Medido, o maior grupo tem 3.
        if ($novoCursor === $cursor && count($dtos) >= $limite) {
            $novoCursor = $cursor + 1;
        }

        $sincronizacao->update([
            'ultimo_updated_at' => $novoCursor,
            'total_processado'  => $sincronizacao->total_processado + count($dtos),
            'executado_em'      => now(),
        ]);

        return $this->resultado($criados, $atualizados, $novoCursor, count($dtos) < $limite);
    }


    public function reiniciarCursor(): void
    {
        $this->cursor()->update(['ultimo_updated_at' => 0, 'total_processado' => 0]);
    }


    /** @return array<int, JogoIgdbDTO> */
    private function buscarLote(int $cursor, int $limite): array
    {
        // >= e não >: updated_at repete entre registros (medido: até 3 por
        // timestamp). Com >, o resto do grupo empatado no fim do lote ficaria
        // para trás em definitivo. Reprocessar é inofensivo — a escrita é
        // idempotente, ancorada em igdb_id.
        $query = sprintf(
            'fields %s; where updated_at >= %d & game_type = 0; sort updated_at asc; limit %d;',
            self::CAMPOS,
            $cursor,
            $limite
        );

        return array_map(
            fn(array $bruto) => JogoIgdbDTO::fromIgdb($bruto),
            $this->igdb->consultar('games', $query)
        );
    }


    /**
     * Uma passada por tipo de entidade, não uma consulta por registro: 500 jogos
     * trazem ~151 entidades distintas. Uma a uma seriam 94 mil consultas ao longo
     * do backfill; assim são ~3.700.
     */
    private function resolverEntidades(array $dtos): array
    {
        return [
            'empresas' => $this->resolver(
                $this->empresaRepositorio,
                $this->coletar($dtos, 'empresas'),
                fn(array $e) => ['igdb_id' => $e['igdb_id'], 'nome' => $e['nome'], 'slug' => $e['slug']],
            ),
            'plataformas' => $this->resolver(
                $this->plataformaRepositorio,
                $this->coletar($dtos, 'plataformas'),
                fn(array $p) => [
                    'igdb_id'    => $p['igdb_id'],
                    'nome'       => $p['nome'],
                    'slug'       => $p['slug'],
                    'abreviacao' => $p['abreviacao'],
                    'geracao'    => $p['geracao'],
                ],
            ),
            'generos' => $this->resolver(
                $this->generoRepositorio,
                $this->coletar($dtos, 'generos'),
                fn(array $g) => ['igdb_id' => $g['igdb_id'], 'nome' => $g['nome'], 'slug' => $g['slug']],
            ),
        ];
    }


    /** Junta e desduplica por igdb_id uma das listas de todos os DTOs do lote. */
    private function coletar(array $dtos, string $propriedade): array
    {
        $porId = [];

        foreach ($dtos as $dto) {
            foreach ($dto->{$propriedade} as $item) {
                $porId[$item['igdb_id']] ??= $item;
            }
        }

        return $porId;
    }


    /** @return array<int, int>  igdb_id => id local */
    private function resolver(
        IEmpresaRepositorio|IPlataformaRepositorio|IGeneroRepositorio $repositorio,
        array $itens,
        callable $paraLinha,
    ): array {
        if ($itens === []) {
            return [];
        }

        $ids = array_keys($itens);
        $mapa = $repositorio->mapaPorIgdbId($ids);
        $faltando = array_diff($ids, array_keys($mapa));

        if ($faltando === []) {
            return $mapa;
        }

        $repositorio->criarEmLote(
            array_map($paraLinha, array_intersect_key($itens, array_flip($faltando)))
        );

        return $repositorio->mapaPorIgdbId($ids);
    }


    /** @return bool  true se criou, false se atualizou */
    private function persistir(JogoIgdbDTO $dto, array $mapas, ?Jogo $existente): bool
    {
        $criou = $existente === null;
        $jogo = $existente ?? new Jogo();

        // Jogo removido continua ocupando o índice único de igdb_id.
        // Restaurar é a única saída — inserir de novo bate no índice.
        if ($jogo->trashed()) {
            $jogo->restore();
        }

        $jogo->igdb_id            = $dto->igdbId;
        $jogo->nome               = $dto->nome;
        $jogo->slug               = $dto->slug;
        $jogo->descricao          = $dto->descricao;
        $jogo->lancamento         = $dto->lancamento;
        $jogo->igdb_atualizado_em = $dto->atualizadoEm;
        $jogo->igdb_imagem_id     = $dto->imagemId;
        $jogo->nota_igdb          = $dto->nota;
        $jogo->tipo_igdb          = $dto->tipo;

        $this->jogoRepositorio->salvarSincronizado(
            $jogo,
            $this->traduzir($dto->plataformas, $mapas['plataformas']),
            $this->traduzir($dto->generos, $mapas['generos']),
            $this->empresasComPapeis($dto, $mapas['empresas']),
        );

        return $criou;
    }


    /** @return array<int, int>  ids locais */
    private function traduzir(array $itens, array $mapa): array
    {
        return array_values(array_filter(
            array_map(fn(array $item) => $mapa[$item['igdb_id']] ?? null, $itens)
        ));
    }


    /** @return array<int, array<string, bool>>  [empresa_id => papéis do pivot] */
    private function empresasComPapeis(JogoIgdbDTO $dto, array $mapa): array
    {
        $vinculos = [];

        foreach ($dto->empresas as $empresa) {
            $id = $mapa[$empresa['igdb_id']] ?? null;

            if ($id === null) {
                continue;
            }

            $vinculos[$id] = [
                'desenvolvedora' => $empresa['desenvolvedora'],
                'publicadora'    => $empresa['publicadora'],
                'portabilidade'  => $empresa['portabilidade'],
                'apoio'          => $empresa['apoio'],
            ];
        }

        return $vinculos;
    }


    /**
     * Defaults explícitos: firstOrCreate insere só os atributos informados e
     * devolve o model sem reler a linha, então os DEFAULT do banco não chegam
     * em memória — ultimo_updated_at viria null na primeira execução.
     */
    private function cursor(): IgdbSincronizacao
    {
        return IgdbSincronizacao::firstOrCreate(
            ['entidade' => self::ENTIDADE],
            ['ultimo_updated_at' => 0, 'total_processado' => 0],
        );
    }


    private function resultado(int $criados, int $atualizados, int $cursor, bool $concluido): array
    {
        return [
            'criados'     => $criados,
            'atualizados' => $atualizados,
            'processados' => $criados + $atualizados,
            'cursor'      => $cursor,
            'concluido'   => $concluido,
        ];
    }
}
