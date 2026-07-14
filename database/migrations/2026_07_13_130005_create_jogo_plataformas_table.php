<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jogo_plataformas', function (Blueprint $table) {
            $table->foreignId('jogo_id')->constrained('jogos');
            $table->foreignId('plataforma_id')->constrained('plataformas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jogo_plataformas');
    }
};
