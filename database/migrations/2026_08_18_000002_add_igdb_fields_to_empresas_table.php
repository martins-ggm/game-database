<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            // Chave natural externa. Nullable porque empresa cadastrada à mão não
            // tem origem no IGDB; unique porque duas linhas não podem reivindicar
            // o mesmo registro de lá. É o que torna o sync idempotente.
            $table->unsignedBigInteger('igdb_id')->nullable()->unique()->after('id');

            $table->string('slug')->nullable()->after('nome');
            $table->unsignedSmallInteger('pais')->nullable()->after('slug'); // ISO 3166-1 numérico
            $table->text('descricao')->nullable()->after('pais');
            $table->string('logo_id')->nullable()->after('descricao');       // image_id do CDN
        });
    }

    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn(['igdb_id', 'slug', 'pais', 'descricao', 'logo_id']);
        });
    }
};
