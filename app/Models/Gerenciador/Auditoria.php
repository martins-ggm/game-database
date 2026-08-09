<?php

namespace App\Models\Gerenciador;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Auditoria extends Model
{
    protected $table = 'auditorias';

    // registro de auditoria é imutável: só temos created_at (sem isso o insert quebra)
    const UPDATED_AT = null;

    protected $fillable = [
        'usuario_id',
        'rota',
        'metodo',
        'alvo_id',
    ];

    public static function criar(int $usuarioId, ?string $rota, string $metodo, ?int $alvoId = null): self
    {
        $auditoria = new self();
        $auditoria->usuario_id = $usuarioId;
        $auditoria->rota = $rota;
        $auditoria->metodo = $metodo;
        $auditoria->alvo_id = $alvoId;

        return $auditoria;
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }
}
