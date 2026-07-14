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
        'nome',
    ];

    public function jogos(): BelongsToMany
    {
        return $this->belongsToMany(Jogo::class, 'jogo_plataformas');
    }
}
