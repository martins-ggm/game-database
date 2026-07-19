<?php

namespace Database\Seeders;

use App\Models\Catalogo\Genero;
use Illuminate\Database\Seeder;

class GeneroSeeder extends Seeder
{
    public function run(): void
    {
        $generos = [
            // --- RPG e variações ---
            'RPG',
            'Action RPG',
            'JRPG',
            'WRPG',
            'SRPG',
            'Tactical RPG',
            'MMORPG',
            'Dungeon Crawler',
            'Roguelike',
            'Roguelite',
            'Monster Tamer',

            // --- Ação ---
            'Action',
            'Action-Adventure',
            'Hack and Slash',
            'Beat \'em up',
            'Character Action',
            'Musou',
            'Stealth',

            // --- Plataforma ---
            'Platformer',
            '2D Platformer',
            '3D Platformer',
            'Metroidvania',
            'Puzzle-Platformer',
            'Run and Gun',

            // --- Tiro ---
            'Shooter',
            'First-Person Shooter',
            'Third-Person Shooter',
            'Tactical Shooter',
            'Hero Shooter',
            'Arena Shooter',
            'Looter Shooter',
            'Extraction Shooter',
            'Battle Royale',
            'Shoot \'em up',
            'Bullet Hell',
            'Light Gun Shooter',

            // --- Luta ---
            'Fighting',
            '2D Fighting',
            '3D Fighting',
            'Platform Fighter',
            'Arena Fighter',

            // --- Aventura ---
            'Adventure',
            'Point-and-Click',
            'Visual Novel',
            'Interactive Fiction',
            'Text Adventure',
            'Walking Simulator',
            'Escape Room',

            // --- Terror ---
            'Horror',
            'Survival Horror',
            'Psychological Horror',

            // --- Estratégia ---
            'Strategy',
            'Real-Time Strategy',
            'Turn-Based Strategy',
            '4X',
            'Grand Strategy',
            'Real-Time Tactics',
            'Turn-Based Tactics',
            'Tower Defense',
            'MOBA',
            'Auto Battler',
            'Wargame',

            // --- Simulação ---
            'Simulation',
            'Life Simulation',
            'Farming Simulation',
            'City Builder',
            'Colony Sim',
            'Tycoon',
            'God Game',
            'Immersive Sim',
            'Dating Sim',
            'Flight Simulator',
            'Vehicle Simulation',

            // --- Sobrevivência / Sandbox ---
            'Survival',
            'Survival Crafting',
            'Sandbox',
            'Open World',
            'Base Building',

            // --- Corrida / Esporte ---
            'Racing',
            'Arcade Racing',
            'Sim Racing',
            'Kart Racing',
            'Sports',
            'Sports Management',

            // --- Puzzle ---
            'Puzzle',
            'Match 3',
            'Physics Puzzle',
            'Hidden Object',
            'Logic',

            // --- Cartas / Tabuleiro ---
            'Card Game',
            'Collectible Card Game',
            'Deck-building',
            'Roguelike Deckbuilder',
            'Board Game',

            // --- Ritmo / Party / Casual ---
            'Rhythm',
            'Music',
            'Party',
            'Trivia',
            'Idle',
            'Clicker',
            'Casual',
            'Arcade',
            'Pinball',
            'Educational',

            // --- Narrativa / Multiplayer ---
            'Narrative',
            'Story-Rich',
            'Interactive Drama',
            'MMO',
        ];

        foreach ($generos as $nome) {
            Genero::firstOrCreate(['nome' => $nome]);
        }
    }
}
