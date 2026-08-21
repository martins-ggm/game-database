<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sem igdb_id nas tabelas de apoio o sync casa por nome, e "Nintendo",
     * "Nintendo Co., Ltd." e "Nintendo EPD" viram três registros distintos.
     */
    public function up(): void
    {
        Schema::table('plataformas', function (Blueprint $table) {
            $table->unsignedBigInteger('igdb_id')->nullable()->unique()->after('id');
            $table->string('slug')->nullable()->after('nome');
            $table->string('abreviacao')->nullable()->after('slug');          // "PS4"
            $table->unsignedSmallInteger('geracao')->nullable()->after('abreviacao');
            $table->string('logo_id')->nullable()->after('geracao');
        });

        Schema::table('generos', function (Blueprint $table) {
            $table->unsignedBigInteger('igdb_id')->nullable()->unique()->after('id');
            $table->string('slug')->nullable()->after('nome');
        });
    }

    public function down(): void
    {
        Schema::table('plataformas', function (Blueprint $table) {
            $table->dropColumn(['igdb_id', 'slug', 'abreviacao', 'geracao', 'logo_id']);
        });

        Schema::table('generos', function (Blueprint $table) {
            $table->dropColumn(['igdb_id', 'slug']);
        });
    }
};
