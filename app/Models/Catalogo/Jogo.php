<?php

namespace App\Models\Catalogo;

use App\Models\Colecao\Colecao;
use App\Models\Gerenciador\Usuario;
use App\Models\Review\Review;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jogo extends Model
{
    use SoftDeletes;

    protected $table = 'jogos';

    const DELETED_AT = 'removido_em';

    protected $fillable = [
        'igdb_id',
        'nome',
        'slug',
        'lancamento',
        'url_imagem_grande',
        'url_imagem_pequena',
        'igdb_imagem_id',
        'igdb_atualizado_em',
        'nota_igdb',
        'tipo_igdb',
        'descricao',
    ];

    protected $casts = [
        'lancamento'         => 'date',
        'igdb_id'            => 'integer',
        'igdb_atualizado_em' => 'integer',
        'nota_igdb'          => 'decimal:2',
        'tipo_igdb'          => 'integer',
    ];

    // ---------------------------------------------------------------- relações

    /** Todas as empresas envolvidas, com os quatro papéis no pivot. */
    public function empresas(): BelongsToMany
    {
        return $this->belongsToMany(Empresa::class, 'jogo_empresas')
            ->withPivot(['desenvolvedora', 'publicadora', 'portabilidade', 'apoio']);
    }

    public function desenvolvedoras(): BelongsToMany
    {
        return $this->empresas()->wherePivot('desenvolvedora', true);
    }

    public function publicadoras(): BelongsToMany
    {
        return $this->empresas()->wherePivot('publicadora', true);
    }

    public function plataformas(): BelongsToMany
    {
        return $this->belongsToMany(Plataforma::class, 'jogo_plataformas');
    }

    public function generos(): BelongsToMany
    {
        return $this->belongsToMany(Genero::class, 'jogo_generos');
    }

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(Usuario::class, 'colecoes', 'jogo_id', 'usuario_id')
            ->withPivot('situacao_id')
            ->withTimestamps();
    }

    public function colecoes(): HasMany
    {
        return $this->hasMany(Colecao::class, 'jogo_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'jogo_id');
    }

    // ------------------------------------------------------------ atributos

    /**
     * Ponte de compatibilidade: mantém $jogo->desenvolvedora respondendo nas
     * views e Resources que existiam antes do pivot. Devolve a primeira
     * desenvolvedora — um jogo pode ter mais de uma, e aí use desenvolvedoras().
     */
    public function getDesenvolvedoraAttribute(): ?Empresa
    {
        return $this->desenvolvedoras->first();
    }

    /**
     * URL da capa no CDN do IGDB. É o que sustenta a estratégia de capa sob
     * demanda: enquanto não existir arquivo local, a imagem vem daqui.
     */
    public function urlCapaIgdb(string $tamanho = 't_cover_big_2x'): ?string
    {
        return $this->igdb_imagem_id
            ? "https://images.igdb.com/igdb/image/upload/{$tamanho}/{$this->igdb_imagem_id}.jpg"
            : null;
    }

    /**
     * Capa a exibir, na ordem: cópia local, depois CDN do IGDB, depois nada.
     *
     * É esta cascata que torna o backfill de imagem desnecessário — o catálogo
     * já nasce com capa, e a cópia local só aparece para os jogos que alguém
     * de fato abriu.
     */
    public function capa(bool $grande = true): ?string
    {
        $local = $grande ? $this->url_imagem_grande : $this->url_imagem_pequena;

        if ($local) {
            return imagem_url($local);
        }

        return $this->urlCapaIgdb($grande ? 't_cover_big_2x' : 't_cover_small_2x');
    }

    /** True quando ainda não há cópia local — o job de capa usa isto. */
    public function precisaBaixarCapa(): bool
    {
        return $this->igdb_imagem_id !== null && $this->url_imagem_grande === null;
    }

    // -------------------------------------------------------------- fábricas

    public static function criar(
        string $nome,
        ?string $lancamento = null,
        ?string $grande = null,
        ?string $pequena = null,
        ?string $descricao = null,
    ): self {
        $jogo = new self();
        $jogo->nome = $nome;
        $jogo->lancamento = $lancamento;
        $jogo->url_imagem_grande = $grande;
        $jogo->url_imagem_pequena = $pequena;
        $jogo->descricao = $descricao;

        return $jogo;
    }

    public function editar(
        string $nome,
        ?string $lancamento = null,
        ?string $descricao = null,
    ): self {
        $this->nome = $nome;
        $this->lancamento = $lancamento;
        $this->descricao = $descricao;

        return $this;
    }
}
