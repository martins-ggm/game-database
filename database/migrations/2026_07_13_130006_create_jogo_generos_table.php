<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jogo_generos', function (Blueprint $table) {
            $table->foreignId('jogo_id')->constrained('jogos');
            $table->foreignId('genero_id')->constrained('generos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jogo_generos');
    }
};
