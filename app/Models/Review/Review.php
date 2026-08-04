<?php

namespace App\Models\Review;

use App\Models\Catalogo\Jogo;
use App\Models\Gerenciador\Usuario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes;

    protected $table = 'reviews';

    const DELETED_AT = 'removido_em';

    protected $fillable = [
        'jogo_id',
        'usuario_id',
        'nota',
        'review',
    ];

    protected $casts = [
        'nota' => 'decimal:1',
    ];

    public function jogo(): BelongsTo
    {
        return $this->belongsTo(Jogo::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }

    public static function criar(int $jogoId, int $usuarioId, float $nota, ?string $review = null): self
    {
        $novaReview = new self();
        $novaReview->jogo_id = $jogoId;
        $novaReview->usuario_id = $usuarioId;
        $novaReview->nota = $nota;
        $novaReview->review = $review;

        return $novaReview;
    }

    public function editar(float $nota, ?string $review = null): self
    {

        $this->nota = $nota;
        $this->review = $review;


        return $this;
    }
}
