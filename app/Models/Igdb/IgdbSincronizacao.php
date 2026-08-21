<?php

namespace App\Models\Igdb;

use Illuminate\Database\Eloquent\Model;

/**
 * Cursor do sync — uma linha por entidade sincronizada.
 *
 * ultimo_updated_at guarda o updated_at DO IGDB em epoch. Zero significa
 * "nunca sincronizei", e como a query é "where updated_at > {cursor}",
 * o backfill inicial percorre o mesmo caminho do incremental.
 */
class IgdbSincronizacao extends Model
{
    protected $table = 'igdb_sincronizacoes';

    protected $fillable = [
        'entidade',
        'ultimo_updated_at',
        'total_processado',
        'executado_em',
    ];

    protected $casts = [
        'ultimo_updated_at' => 'integer',
        'total_processado'  => 'integer',
        'executado_em'      => 'datetime',
    ];
}
