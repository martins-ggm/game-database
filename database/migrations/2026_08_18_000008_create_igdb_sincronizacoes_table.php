<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cursor do sync — uma linha por entidade sincronizada.
     *
     * ultimo_updated_at = 0 significa "nunca sincronizei", e como a query é
     * "where updated_at > {cursor}", o backfill inicial cai no mesmo caminho
     * do incremental. Sem caso especial.
     */
    public function up(): void
    {
        Schema::create('igdb_sincronizacoes', function (Blueprint $table) {
            $table->id();
            $table->string('entidade')->unique();                       // 'games'
            $table->unsignedBigInteger('ultimo_updated_at')->default(0); // epoch do IGDB
            $table->unsignedBigInteger('total_processado')->default(0);
            $table->timestamp('executado_em')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('igdb_sincronizacoes');
    }
};
