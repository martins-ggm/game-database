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
        Schema::table('reviews', function (Blueprint $table) {
            // curtidas/descurtidas vão para uma tabela auxiliar própria depois
            $table->dropColumn(['aprovacao', 'reprovacao']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedInteger('aprovacao')->default(0);
            $table->unsignedInteger('reprovacao')->default(0);
        });
    }
};
