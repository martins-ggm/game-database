<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('colecoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jogo_id')->constrained('jogos');
            $table->foreignId('usuario_id')->constrained('sg_usuarios');
            $table->foreignId('situacao_id')->constrained('situacoes');
            $table->timestamps();

            // um mesmo jogo não pode aparecer duas vezes na coleção do mesmo usuário
            $table->unique(['jogo_id', 'usuario_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colecoes');
    }
};
