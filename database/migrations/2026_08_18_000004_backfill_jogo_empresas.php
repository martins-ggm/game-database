<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Migração de DADOS, separada da de esquema de propósito: permite conferir
     * a contagem antes de derrubar jogos.desenvolvedora_id no passo seguinte.
     *
     * Usa DB::table (não o model) para incluir jogos com removido_em preenchido —
     * a relação deve sobreviver ao soft delete.
     */
    public function up(): void
    {
        DB::table('jogos')
            ->whereNotNull('desenvolvedora_id')
            ->orderBy('id')
            ->chunkById(500, function ($jogos) {
                $linhas = collect($jogos)->map(fn ($jogo) => [
                    'jogo_id'        => $jogo->id,
                    'empresa_id'     => $jogo->desenvolvedora_id,
                    'desenvolvedora' => true,
                    'publicadora'    => false,
                    'portabilidade'  => false,
                    'apoio'          => false,
                ])->all();

                DB::table('jogo_empresas')->insertOrIgnore($linhas);
            });
    }

    /**
     * Remove apenas as linhas com a assinatura deste backfill (só desenvolvedora),
     * para não apagar vínculos que o sync do IGDB tenha criado depois.
     */
    public function down(): void
    {
        DB::table('jogo_empresas')
            ->where('desenvolvedora', true)
            ->where('publicadora', false)
            ->where('portabilidade', false)
            ->where('apoio', false)
            ->delete();
    }
};
