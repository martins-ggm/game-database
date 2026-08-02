<?php

namespace Database\Seeders;

use App\Models\Colecao\Situacao;
use Illuminate\Database\Seeder;

class SituacaoSeeder extends Seeder
{
    public function run(): void
    {
        $situacoes = [
            // --- em andamento ---
            'Jogando',
            'Rejogando',
            'Pausado',

            // --- concluídos ---
            'Zerado',
            'Platinado',

            // --- pretende jogar ---
            'Na lista',

            // --- encerrados ---
            'Dropado',

            // --- marcadores ---
            'Jogaria de novo',
        ];

        foreach ($situacoes as $nome) {
            Situacao::firstOrCreate(['nome' => $nome]);
        }
    }
}
