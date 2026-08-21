<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Só rodar depois de conferir o backfill do passo 4:
     *   select count(*) from jogo_empresas where desenvolvedora;
     *   select count(*) from jogos where desenvolvedora_id is not null;
     * Os dois números têm que bater.
     */
    public function up(): void
    {
        Schema::table('jogos', function (Blueprint $table) {
            $table->dropForeign(['desenvolvedora_id']);
            $table->dropColumn('desenvolvedora_id');
        });
    }

    /**
     * Reversível: recria a coluna e repovoa a partir do pivot.
     * Perda inerente — jogo com dois desenvolvedores volta com apenas um,
     * porque belongsTo não comporta o resto. Sintaxe UPDATE..FROM é do Postgres,
     * banco em que este projeto já está acoplado (repositórios usam ilike).
     */
    public function down(): void
    {
        Schema::table('jogos', function (Blueprint $table) {
            $table->foreignId('desenvolvedora_id')->nullable()->after('nome')->constrained('empresas');
        });

        DB::statement('
            UPDATE jogos
               SET desenvolvedora_id = je.empresa_id
              FROM jogo_empresas je
             WHERE je.jogo_id = jogos.id
               AND je.desenvolvedora = true
        ');
    }
};
