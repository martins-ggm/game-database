<?php

namespace App\Models\Catalogo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Antes "Desenvolvedora". No IGDB existe um único conjunto de empresas — a mesma
 * companhia é desenvolvedora num jogo e publicadora noutro. O papel vive no
 * pivot jogo_empresas, não aqui.
 */
class Empresa extends Model
{
    use SoftDeletes;

    protected $table = 'empresas';

    const DELETED_AT = 'removido_em';

    protected $fillable = [
        'igdb_id',
        'nome',
        'slug',
        'pais',
        'descricao',
        'logo_id',
    ];

    protected $casts = [
        'igdb_id' => 'integer',
        'pais'    => 'integer',
    ];

    public function jogos(): BelongsToMany
    {
        return $this->belongsToMany(Jogo::class, 'jogo_empresas')
            ->withPivot(['desenvolvedora', 'publicadora', 'portabilidade', 'apoio']);
    }

    public function jogosDesenvolvidos(): BelongsToMany
    {
        return $this->jogos()->wherePivot('desenvolvedora', true);
    }

    public function jogosPublicados(): BelongsToMany
    {
        return $this->jogos()->wherePivot('publicadora', true);
    }

    /** URL do logo no CDN do IGDB, quando houver. */
    public function urlLogo(string $tamanho = 't_logo_med'): ?string
    {
        return $this->logo_id
            ? "https://images.igdb.com/igdb/image/upload/{$tamanho}/{$this->logo_id}.png"
            : null;
    }

    public static function criar(
        string $nome,
        ?int $igdbId = null,
        ?string $slug = null,
        ?int $pais = null,
        ?string $descricao = null,
        ?string $logoId = null,
    ): self {
        $empresa = new self();
        $empresa->nome = $nome;
        $empresa->igdb_id = $igdbId;
        $empresa->slug = $slug;
        $empresa->pais = $pais;
        $empresa->descricao = $descricao;
        $empresa->logo_id = $logoId;

        return $empresa;
    }

    public function editar(
        string $nome,
        ?string $slug = null,
        ?int $pais = null,
        ?string $descricao = null,
        ?string $logoId = null,
    ): self {
        $this->nome = $nome;
        $this->slug = $slug;
        $this->pais = $pais;
        $this->descricao = $descricao;
        $this->logo_id = $logoId;

        return $this;
    }
}
