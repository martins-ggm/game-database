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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jogo_id')->constrained('jogos');
            $table->foreignId('usuario_id')->constrained('sg_usuarios');
            $table->decimal('nota', 2, 1);                     // 0.0 – 5.0
            $table->text('review');
            $table->unsignedInteger('aprovacao')->default(0);  // curtidas
            $table->unsignedInteger('reprovacao')->default(0); // descurtidas
            $table->timestamps();
            $table->softDeletes('removido_em');
            // uma review por usuário por jogo
            $table->unique(['jogo_id', 'usuario_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
