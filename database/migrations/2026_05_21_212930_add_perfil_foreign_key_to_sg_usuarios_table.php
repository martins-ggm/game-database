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
        Schema::table('sg_usuarios', function (Blueprint $table) {
            $table->foreign('perfil_id')
                ->references('id')
                ->on('perfil')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sg_usuarios', function (Blueprint $table) {
            $table->dropForeign(['perfil_id']);
        });
    }
};
