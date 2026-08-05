<?php

namespace App\Models\Gerenciador;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatchNote extends Model
{
    use SoftDeletes;

    protected $table = 'patch_notes';

    const DELETED_AT = 'removido_em';

    protected $fillable = [
        'versao',
        'titulo',
        'mudancas',
        'lancado_em',
    ];

    protected $casts = [
        'mudancas' => 'array',
        'lancado_em' => 'date',
    ];

    /**
     * @param  array<int, array{tipo: string, texto: string}>  $mudancas
     */
    public static function criar(string $versao, string $titulo, array $mudancas, string $lancadoEm): self
    {
        $patchNote = new self();
        $patchNote->versao = $versao;
        $patchNote->titulo = $titulo;
        $patchNote->mudancas = $mudancas;
        $patchNote->lancado_em = $lancadoEm;

        return $patchNote;
    }

    /**
     * @param  array<int, array{tipo: string, texto: string}>  $mudancas
     */
    public function editar(string $titulo, array $mudancas, string $lancadoEm): self
    {
        $this->titulo = $titulo;
        $this->mudancas = $mudancas;
        $this->lancado_em = $lancadoEm;

        return $this;
    }
}
