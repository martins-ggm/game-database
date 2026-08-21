<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'nome' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Catálogo (jogos, empresas, plataformas, gêneros) vem do IGDB:
        //   php artisan igdb:sincronizar
        // Semear essas tabelas à mão recria registros sem igdb_id, e o sync
        // passa a criar duplicatas em vez de atualizar. Aqui ficam apenas as
        // tabelas que o IGDB não fornece.
        $this->call([
            SituacaoSeeder::class,
            PatchNoteSeeder::class,
        ]);
    }
}
