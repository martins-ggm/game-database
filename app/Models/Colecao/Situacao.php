<?php

namespace App\Models\Colecao;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Situacao extends Model
{
    // "Situacao" pluralizaria errado (situacaos), então fixo o nome da tabela.
    protected $table = 'situacoes';

    protected $fillable = ['nome'];

    public function colecoes(): HasMany
    {
        return $this->hasMany(Colecao::class, 'situacao_id');
    }
}
