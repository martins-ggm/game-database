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
                'versao' => '0.9.0',
                'titulo' => 'Catálogo alimentado pelo IGDB',
                'lancado_em' => '2026-08-20',
                'mudancas' => [
                    ['tipo' => 'novo', 'texto' => 'O acervo agora vem da API do IGDB e se atualiza sozinho — jogos, empresas, plataformas e gêneros.'],
                    ['tipo' => 'melhoria', 'texto' => 'Crédito ao IGDB no rodapé, como pede o uso da API.'],
                ],
            ],
            [
                'versao' => '0.8.0',
                'titulo' => 'Reviews no perfil',
                'lancado_em' => '2026-08-07',
                'mudancas' => [
                    ['tipo' => 'novo', 'texto' => 'Seção de últimas reviews no perfil, com nota, capa e comentário reais.'],
                    ['tipo' => 'novo', 'texto' => 'Tela dedicada com todas as reviews de um usuário, em duas colunas e com paginação.'],
                    ['tipo' => 'melhoria', 'texto' => 'O nome de quem escreveu uma review agora leva ao perfil do autor.'],
                ],
            ],
            [
                'versao' => '0.7.0',
                'titulo' => 'Catálogo repaginado',
                'lancado_em' => '2026-08-06',
                'mudancas' => [
                    ['tipo' => 'novo', 'texto' => 'Catálogo reformulado: todos os jogos numa grade única com paginação, ordenados pelos mais avaliados.'],
                    ['tipo' => 'melhoria', 'texto' => 'Grade do catálogo preenche a largura da tela em qualquer tamanho, com colunas e cards que se ajustam automaticamente.'],
                    ['tipo' => 'melhoria', 'texto' => 'Seções de destaque passam a considerar todo o acervo, não apenas jogos com review recente.'],
                ],
            ],
            [
                'versao' => '0.6.0',
                'titulo' => 'Paginação, destaques & changelog',
                'lancado_em' => '2026-08-05',
                'mudancas' => [
                    ['tipo' => 'novo', 'texto' => 'Paginação nas listagens — no catálogo administrativo de jogos (com busca) e no próprio changelog.'],
                    ['tipo' => 'novo', 'texto' => 'Seção de patch notes na home, com a versão atual exibida no rodapé de todas as telas.'],
                    ['tipo' => 'novo', 'texto' => 'Jogos em destaque na home, puxando os mais avaliados recentemente.'],
                    ['tipo' => 'melhoria', 'texto' => 'Rodapé padronizado em todas as telas via componente único.'],
                    ['tipo' => 'melhoria', 'texto' => 'Rolagem horizontal nas seções da home em telas menores.'],
                ],
            ],
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
