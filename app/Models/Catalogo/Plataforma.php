<?php

namespace App\Models\Catalogo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plataforma extends Model
{
    use SoftDeletes;

    protected $table = 'plataformas';

    const DELETED_AT = 'removido_em';

    protected $fillable = [
        'igdb_id',
        'nome',
        'slug',
        'abreviacao',
        'geracao',
        'logo_id',
        'lancamento',
    ];

    protected $casts = [
        'lancamento' => 'date',
        'igdb_id'    => 'integer',
        'geracao'    => 'integer',
    ];

    public function jogos(): BelongsToMany
    {
        return $this->belongsToMany(Jogo::class, 'jogo_plataformas');
    }

    /** Rótulo curto para selos de card: "PS4" quando existir, senão o nome. */
    public function rotuloCurto(): string
    {
        return $this->abreviacao ?: $this->nome;
    }

    public static function criar(
        string $nome,
        ?string $lancamento = null,
        ?int $igdbId = null,
        ?string $slug = null,
        ?string $abreviacao = null,
        ?int $geracao = null,
        ?string $logoId = null,
    ): self {
        $plataforma = new self();
        $plataforma->nome = $nome;
        $plataforma->lancamento = $lancamento;
        $plataforma->igdb_id = $igdbId;
        $plataforma->slug = $slug;
        $plataforma->abreviacao = $abreviacao;
        $plataforma->geracao = $geracao;
        $plataforma->logo_id = $logoId;

        return $plataforma;
    }

    public function editar(
        string $nome,
        ?string $lancamento = null,
        ?string $abreviacao = null,
        ?int $geracao = null,
    ): self {
        $this->nome = $nome;
        $this->lancamento = $lancamento;
        $this->abreviacao = $abreviacao;
        $this->geracao = $geracao;

        return $this;
    }
}
