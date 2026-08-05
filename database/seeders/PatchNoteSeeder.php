<?php

namespace Database\Seeders;

use App\Models\Gerenciador\PatchNote;
use Illuminate\Database\Seeder;

class PatchNoteSeeder extends Seeder
{
    public function run(): void
    {
        $notas = [
            [
                'versao' => '0.5.0',
                'titulo' => 'Em alta & Catálogo',
                'lancado_em' => '2026-08-05',
                'mudancas' => [
                    ['tipo' => 'novo', 'texto' => 'Tela de catálogo com jogos organizados por gênero.'],
                    ['tipo' => 'novo', 'texto' => 'Seção "em alta" destacando os gêneros mais avaliados dos últimos 30 dias.'],
                    ['tipo' => 'novo', 'texto' => 'Filtro de gêneros para explorar o catálogo.'],
                    ['tipo' => 'melhoria', 'texto' => 'Algoritmos de listagem refinados para rankear por reviews recentes.'],
                ],
            ],
            [
                'versao' => '0.4.0',
                'titulo' => 'Sistema de reviews',
                'lancado_em' => '2026-07-28',
                'mudancas' => [
                    ['tipo' => 'novo', 'texto' => 'Avaliações com nota e comentário direto na tela do jogo.'],
                    ['tipo' => 'novo', 'texto' => 'Foto de perfil e nome do autor em cada review.'],
                    ['tipo' => 'melhoria', 'texto' => 'Média de notas exibida no topo da página do jogo.'],
                ],
            ],
            [
                'versao' => '0.3.0',
                'titulo' => 'Coleções',
                'lancado_em' => '2026-07-20',
                'mudancas' => [
                    ['tipo' => 'novo', 'texto' => 'Adicione jogos à sua coleção com uma situação (jogando, na lista, dropado...).'],
                    ['tipo' => 'novo', 'texto' => 'Tela de coleção no perfil, com filtro por situação.'],
                    ['tipo' => 'correcao', 'texto' => 'Correção na renderização dos últimos jogos do perfil.'],
                ],
            ],
        ];

        foreach ($notas as $nota) {
            // idempotente: identifica pela versão, não duplica em re-seed
            PatchNote::firstOrCreate(
                ['versao' => $nota['versao']],
                [
                    'titulo' => $nota['titulo'],
                    'mudancas' => $nota['mudancas'],
                    'lancado_em' => $nota['lancado_em'],
                ]
            );
        }

        $this->command->info('Patch notes garantidos: ' . count($notas));
    }
}
