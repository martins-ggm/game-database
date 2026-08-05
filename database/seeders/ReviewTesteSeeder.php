<?php

namespace Database\Seeders;

use App\Models\Catalogo\Jogo;
use App\Models\Gerenciador\Usuario;
use App\Models\Review\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ReviewTesteSeeder extends Seeder
{
    public function run(): void
    {
        // ---------- 1. usuários de teste ----------
        $nomes = [
            'Ryn Kovalenko', 'Kaz Moreau', 'Mel Fontaine', 'Dex Nakamura', 'Vi Solano',
            'Juno Baptista', 'Nix Cardoso', 'Lena Vasquez', 'Theo Almeida', 'Suki Tanaka',
            'Bruno Xavier', 'Ivy Petrova', 'Caio Bernardes', 'Nova Reyes', 'Igor Mendes',
        ];

        $usuarios = collect($nomes)->map(fn ($nome) => Usuario::firstOrCreate(
            ['email' => Str::slug($nome, '.') . '@teste.com'],
            [
                'nome' => $nome,
                'password' => 'password', // o cast 'hashed' do model já criptografa
                'perfil_id' => null,
            ]
        ));

        $this->command->info("Usuários de teste garantidos: {$usuarios->count()}");

        // ---------- 2. pools de texto por sentimento ----------
        $positivas = [
            'Experiência absurda do início ao fim. Level design impecável.',
            'Um dos melhores que já joguei, cada detalhe pensado com carinho.',
            'Trilha sonora inesquecível e gameplay viciante. Recomendo demais.',
            'Simplesmente perfeito. Zerei e já quero jogar de novo.',
            'Mundo riquíssimo, personagens carismáticos. Vale cada minuto.',
            'Me prendeu por horas sem ver o tempo passar. Obra-prima.',
            'Combate fluido, história envolvente. Sem defeitos pra mim.',
        ];
        $mistas = [
            'Muito bom, mas o ritmo cai um pouco no meio. Ainda assim recomendo.',
            'Divertido, porém fica repetitivo depois de umas horas.',
            'Boa premissa, execução mediana. Dá pra passar o tempo.',
            'Gostei, mas esperava mais do final. Fica no "ok".',
            'Tem momentos brilhantes e outros bem esquecíveis.',
        ];
        $negativas = [
            'Começou promissor mas me decepcionou no fim.',
            'Muito bug e o controle travado. Cansei rápido.',
            'Não é pra mim. Achei arrastado e sem graça.',
            'Esperava bem mais pelo hype. Dropei na metade.',
        ];

        // ---------- 3. reviews espalhadas em ~60 jogos ----------
        $jogosEscolhidos = Jogo::inRandomOrder()->take(60)->pluck('id');

        $criadas = 0;

        foreach ($jogosEscolhidos as $jogoId) {
            // 1 a 4 avaliações por jogo, de usuários distintos
            $autores = $usuarios->shuffle()->take(rand(1, 4));

            foreach ($autores as $autor) {
                // respeita o unique(jogo_id, usuario_id)
                $jaExiste = Review::withTrashed()
                    ->where('jogo_id', $jogoId)
                    ->where('usuario_id', $autor->id)
                    ->exists();
                if ($jaExiste) {
                    continue;
                }

                $nota = $this->notaPonderada();
                $texto = match (true) {
                    $nota >= 4 => $positivas[array_rand($positivas)],
                    $nota === 3 => $mistas[array_rand($mistas)],
                    default => $negativas[array_rand($negativas)],
                };

                $data = Carbon::now()->subDays(rand(0, 120))->subHours(rand(0, 23));

                $review = Review::criar($jogoId, $autor->id, $nota, $texto);
                $review->created_at = $data;
                $review->updated_at = $data;
                $review->save();

                $criadas++;
            }
        }

        $this->command->info("Reviews criadas: {$criadas}");
    }

    /** Nota 1–5 com peso pra notas altas (5:30% 4:30% 3:20% 2:12% 1:8%). */
    private function notaPonderada(): int
    {
        $r = rand(1, 100);

        return match (true) {
            $r <= 30 => 5,
            $r <= 60 => 4,
            $r <= 80 => 3,
            $r <= 92 => 2,
            default => 1,
        };
    }
}
