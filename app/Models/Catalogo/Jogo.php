<?php

namespace App\Models\Catalogo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jogo extends Model
{
    use SoftDeletes;

    protected $table = 'jogos';

    const DELETED_AT = 'removido_em';

    protected $fillable = [
        'nome',
        'desenvolvedora_id',
        'url_imagem_grande',
        'url_imagem_pequena'
    ];

    public function desenvolvedora(): BelongsTo
    {
        return $this->belongsTo(Desenvolvedora::class);
    }

    public function plataformas(): BelongsToMany
    {
        return $this->belongsToMany(Plataforma::class, 'jogo_plataformas');
    }

    public function generos(): BelongsToMany
    {
        return $this->belongsToMany(Genero::class, 'jogo_generos');
    }

    public static function criar(String $nome, int $desenvolvedora, ?string $grande = null, ?string $pequena = null): self
    {

        $jogo = new self();
        $jogo->nome = $nome;
        $jogo->desenvolvedora_id = $desenvolvedora;
        $jogo->url_imagem_grande = $grande;
        $jogo->url_imagem_pequena = $pequena;

        return $jogo;
    }

    public function editar(String $nome, int $desenvolvedora): self
    {
        $this->nome = $nome;
        $this->desenvolvedora_id = $desenvolvedora;

        return $this;
    }
}
