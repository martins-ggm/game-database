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
            $table->text('str_url_foto_perfil')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sg_usuarios', function (Blueprint $table) {
            $table->dropColumn('str_url_foto_perfil');
        });
    }
};
