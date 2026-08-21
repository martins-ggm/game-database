<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Espelha involved_companies do IGDB: quatro booleanos numa linha só.
     * Uma empresa pode ser desenvolvedora E publicadora do mesmo jogo
     * (Nintendo na maioria dos títulos dela) — daí booleanos em vez de
     * uma coluna "papel", que exigiria duas linhas para o mesmo caso.
     */
    public function up(): void
    {
        Schema::create('jogo_empresas', function (Blueprint $table) {
            $table->foreignId('jogo_id')->constrained('jogos');
            $table->foreignId('empresa_id')->constrained('empresas');

            $table->boolean('desenvolvedora')->default(false);
            $table->boolean('publicadora')->default(false);
            $table->boolean('portabilidade')->default(false);
            $table->boolean('apoio')->default(false);

            $table->primary(['jogo_id', 'empresa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jogo_empresas');
    }
};
