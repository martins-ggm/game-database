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
        Schema::create('patch_notes', function (Blueprint $table) {
            $table->id();
            $table->string('versao')->unique();   // ex.: 0.5.0
            $table->string('titulo');             // ex.: Em alta & Catálogo
            $table->json('mudancas');             // [{ "tipo": "novo", "texto": "..." }, ...]
            $table->date('lancado_em');           // data exibida no changelog
            $table->timestamps();
            $table->softDeletes('removido_em');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patch_notes');
    }
};
