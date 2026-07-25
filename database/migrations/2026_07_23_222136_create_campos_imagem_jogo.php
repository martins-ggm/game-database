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
        Schema::table('jogos', function (Blueprint $table) {
            $table->string('url_imagem_grande')->nullable()->after('desenvolvedora_id');
            $table->string('url_imagem_pequena')->nullable()->after('url_imagem_grande');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jogos', function (Blueprint $table) {
            $table->dropColumn(['url_imagem_grande', 'url_imagem_pequena']);
        });
    }
};
