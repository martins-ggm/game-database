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
        Schema::create('auditorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('sg_usuarios'); // quem fez a ação
            $table->string('rota')->nullable();                          // nome da rota, ex.: catalogo.jogo.criar
            $table->string('metodo', 10);                                // método HTTP (POST, ...)
            $table->unsignedBigInteger('alvo_id')->nullable();           // {id} da rota (editar/remover); null no criar
            $table->timestamp('created_at')->useCurrent()->index();      // quando — sem updated_at (registro imutável)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auditorias');
    }
};
