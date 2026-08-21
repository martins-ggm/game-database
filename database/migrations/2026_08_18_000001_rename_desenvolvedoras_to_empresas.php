<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * "Desenvolvedora" descrevia o papel, não a entidade. No IGDB existe um único
     * conjunto de empresas — a mesma companhia é desenvolvedora num jogo e
     * publicadora noutro. O papel passa a viver na relação (jogo_empresas).
     *
     * Rename preserva os dados e a foreign key de jogos.desenvolvedora_id.
     */
    public function up(): void
    {
        Schema::rename('desenvolvedoras', 'empresas');

        // Schema::rename() renomeia a TABELA, não a sequence que alimenta o id.
        // Sem isto sobra "desenvolvedoras_id_seq" — inofensivo em runtime, mas
        // quebra qualquer ALTER SEQUENCE que assuma o nome padrão do Laravel.
        DB::statement('ALTER SEQUENCE IF EXISTS desenvolvedoras_id_seq RENAME TO empresas_id_seq');
    }

    public function down(): void
    {
        DB::statement('ALTER SEQUENCE IF EXISTS empresas_id_seq RENAME TO desenvolvedoras_id_seq');

        Schema::rename('empresas', 'desenvolvedoras');
    }
};
