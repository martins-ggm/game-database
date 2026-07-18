<?php

namespace Database\Seeders;

use App\Models\Catalogo\Desenvolvedora;
use Illuminate\Database\Seeder;

class DesenvolvedoraSeeder extends Seeder
{
    public function run(): void
    {
        $desenvolvedoras = [
            '343 Industries',
            'Arc System Works',
            'Arkane Studios',
            'Atlus',
            'Avalanche Studios',
            'Bandai Namco Studios',
            'Bethesda Game Studios',
            'BioWare',
            'Blizzard Entertainment',
            'Bungie',
            'Capcom',
            'CD Projekt Red',
            'Coffee Stain Studios',
            'ConcernedApe',
            'Creative Assembly',
            'Crystal Dynamics',
            'DICE',
            'Dontnod Entertainment',
            'Double Fine Productions',
            'Epic Games',
            'Firaxis Games',
            'FromSoftware',
            'Game Freak',
            'Gearbox Software',
            'Grasshopper Manufacture',
            'Guerrilla Games',
            'HAL Laboratory',
            'Hazelight Studios',
            'id Software',
            'Infinity Ward',
            'Innersloth',
            'Insomniac Games',
            'Intelligent Systems',
            'IO Interactive',
            'Klei Entertainment',
            'Kojima Productions',
            'Konami',
            'Larian Studios',
            'MachineGames',
            'Media Molecule',
            'miHoYo',
            'Mojang Studios',
            'Monolith Soft',
            'Moon Studios',
            'Naughty Dog',
            'Nintendo EPD',
            'Obsidian Entertainment',
            'Platinum Games',
            'Playdead',
            'Playground Games',
            'Rare',
            'Re-Logic',
            'Remedy Entertainment',
            'Respawn Entertainment',
            'Retro Studios',
            'Riot Games',
            'Rockstar Games',
            'Santa Monica Studio',
            'Sega',
            'Square Enix',
            'Studio MDHR',
            'Sucker Punch Productions',
            'Supergiant Games',
            'Team Cherry',
            'Techland',
            'thatgamecompany',
            'Treyarch',
            'tri-Ace',
            'Ubisoft Montreal',
            'Valve',
        ];

        foreach ($desenvolvedoras as $nome) {
            Desenvolvedora::firstOrCreate(['nome' => $nome]);
        }
    }
}
