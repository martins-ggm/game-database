<?php

namespace App\Models\Catalogo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Genero extends Model
{
    use SoftDeletes;

    protected $table = 'generos';

    const DELETED_AT = 'removido_em';

    protected $fillable = [
        'nome',
    ];

    public function jogos(): BelongsToMany
    {
        return $this->belongsToMany(Jogo::class, 'jogo_generos');
    }

    public static function criar(String $nome): self
    {
        $genero = new self();
        $genero->nome = $nome;
        return $genero;
    }

    public function editar(String $nome): self
    {

        $this->nome = $nome;

        return $this;
    }
}
