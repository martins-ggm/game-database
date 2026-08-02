<?php

namespace App\Models\Colecao;

use App\Models\Catalogo\Jogo;
use App\Models\Colecao\Situacao;
use App\Models\Gerenciador\Usuario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Colecao extends Model
{
    // "Colecao" pluralizaria como "colecaos" — fixo o nome real da tabela.
    protected $table = 'colecoes';

    protected $fillable = [
        'jogo_id',
        'usuario_id',
        'situacao_id',
    ];

    public function jogo(): BelongsTo
    {
        return $this->belongsTo(Jogo::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }

    public function situacao(): BelongsTo
    {
        return $this->belongsTo(Situacao::class);
    }

    public static function criar(int $jogoId, int $usuarioId, int $situacaoId): self
    {
        $colecao = new self();
        $colecao->jogo_id = $jogoId;
        $colecao->usuario_id = $usuarioId;
        $colecao->situacao_id = $situacaoId;

        return $colecao;
    }
}
