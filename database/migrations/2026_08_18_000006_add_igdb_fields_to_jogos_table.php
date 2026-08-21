<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jogos', function (Blueprint $table) {
            $table->unsignedBigInteger('igdb_id')->nullable()->unique()->after('id');
            $table->string('slug')->nullable()->unique()->after('nome');

            // image_id da capa no CDN do IGDB. Central na estratégia de capa sob
            // demanda: monta a URL remota enquanto não existir arquivo local.
            $table->string('igdb_imagem_id')->nullable()->after('url_imagem_pequena');

            // updated_at DO IGDB, em epoch — comparado direto contra o valor da API,
            // sem conversão de ida e volta. Campo de máquina, não de tela.
            $table->unsignedBigInteger('igdb_atualizado_em')->nullable()->after('igdb_imagem_id');

            $table->decimal('nota_igdb', 5, 2)->nullable()->after('igdb_atualizado_em'); // total_rating
            $table->unsignedSmallInteger('tipo_igdb')->nullable()->after('nota_igdb');   // game_type
        });
    }

    public function down(): void
    {
        Schema::table('jogos', function (Blueprint $table) {
            $table->dropColumn([
                'igdb_id', 'slug', 'igdb_imagem_id',
                'igdb_atualizado_em', 'nota_igdb', 'tipo_igdb',
            ]);
        });
    }
};
