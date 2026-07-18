<?php

namespace App\Models\Catalogo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Desenvolvedora extends Model
{
    use SoftDeletes;

    protected $table = 'desenvolvedoras';

    const DELETED_AT = 'removido_em';

    protected $fillable = [
        'nome',
    ];

    public function jogos(): HasMany
    {
        return $this->hasMany(Jogo::class);
    }


    public static function criar(String $nome): self
    {
        $desenvolvedora = new self();
        $desenvolvedora->nome = $nome;

        return $desenvolvedora;
    }
}
