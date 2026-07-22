<?php

namespace Database\Seeders;

use App\Models\Catalogo\Desenvolvedora;
use App\Models\Catalogo\Genero;
use App\Models\Catalogo\Jogo;
use App\Models\Catalogo\Plataforma;
use Illuminate\Database\Seeder;

class JogoSeeder extends Seeder
{
    public function run(): void
    {
        // Mapas nome => id, carregados UMA vez (3 queries no total).
        // Assim a seed acha os ids sozinha, em qualquer banco.
        $mapaDesenvolvedoras = Desenvolvedora::pluck('id', 'nome');
        $mapaPlataformas     = Plataforma::pluck('id', 'nome');
        $mapaGeneros         = Genero::pluck('id', 'nome');

        foreach ($this->jogos() as $dados) {

            $jogo = Jogo::firstOrCreate(
                ['nome' => $dados['nome']],
                ['desenvolvedora_id' => $mapaDesenvolvedoras[$dados['desenvolvedora']] ?? null],
            );

            $jogo->plataformas()->sync(
                collect($dados['plataformas'])
                    ->map(fn ($nome) => $mapaPlataformas[$nome] ?? null)
                    ->filter()   // descarta o que não existir no banco
                    ->all()
            );

            $jogo->generos()->sync(
                collect($dados['generos'])
                    ->map(fn ($nome) => $mapaGeneros[$nome] ?? null)
                    ->filter()
                    ->all()
            );
        }
    }

    private function jogos(): array
    {
        return [
            // ---------- Nintendo ----------
            [
                'nome' => 'The Legend of Zelda: Breath of the Wild',
                'desenvolvedora' => 'Nintendo EPD',
                'plataformas' => ['Wii U', 'Nintendo Switch'],
                'generos' => ['Action-Adventure', 'Open World'],
            ],
            [
                'nome' => 'The Legend of Zelda: Tears of the Kingdom',
                'desenvolvedora' => 'Nintendo EPD',
                'plataformas' => ['Nintendo Switch'],
                'generos' => ['Action-Adventure', 'Open World'],
            ],
            [
                'nome' => 'Super Mario Odyssey',
                'desenvolvedora' => 'Nintendo EPD',
                'plataformas' => ['Nintendo Switch'],
                'generos' => ['3D Platformer', 'Platformer'],
            ],
            [
                'nome' => 'Super Mario 64',
                'desenvolvedora' => 'Nintendo EPD',
                'plataformas' => ['Nintendo 64'],
                'generos' => ['3D Platformer', 'Platformer'],
            ],
            [
                'nome' => 'Animal Crossing: New Horizons',
                'desenvolvedora' => 'Nintendo EPD',
                'plataformas' => ['Nintendo Switch'],
                'generos' => ['Life Simulation', 'Casual'],
            ],
            [
                'nome' => 'Splatoon 3',
                'desenvolvedora' => 'Nintendo EPD',
                'plataformas' => ['Nintendo Switch'],
                'generos' => ['Third-Person Shooter', 'Shooter'],
            ],
            [
                'nome' => 'Pokémon Red',
                'desenvolvedora' => 'Game Freak',
                'plataformas' => ['Game Boy'],
                'generos' => ['JRPG', 'Monster Tamer', 'RPG'],
            ],
            [
                'nome' => 'Pokémon Scarlet',
                'desenvolvedora' => 'Game Freak',
                'plataformas' => ['Nintendo Switch'],
                'generos' => ['JRPG', 'Monster Tamer', 'Open World'],
            ],
            [
                'nome' => "Kirby's Dream Land",
                'desenvolvedora' => 'HAL Laboratory',
                'plataformas' => ['Game Boy'],
                'generos' => ['2D Platformer', 'Platformer'],
            ],
            [
                'nome' => 'Kirby and the Forgotten Land',
                'desenvolvedora' => 'HAL Laboratory',
                'plataformas' => ['Nintendo Switch'],
                'generos' => ['3D Platformer', 'Action-Adventure'],
            ],
            [
                'nome' => 'Fire Emblem: Three Houses',
                'desenvolvedora' => 'Intelligent Systems',
                'plataformas' => ['Nintendo Switch'],
                'generos' => ['SRPG', 'Tactical RPG', 'JRPG'],
            ],
            [
                'nome' => 'Metroid Prime',
                'desenvolvedora' => 'Retro Studios',
                'plataformas' => ['Nintendo GameCube'],
                'generos' => ['First-Person Shooter', 'Action-Adventure', 'Metroidvania'],
            ],
            [
                'nome' => 'Donkey Kong Country: Tropical Freeze',
                'desenvolvedora' => 'Retro Studios',
                'plataformas' => ['Wii U', 'Nintendo Switch'],
                'generos' => ['2D Platformer', 'Platformer'],
            ],
            [
                'nome' => 'Xenoblade Chronicles',
                'desenvolvedora' => 'Monolith Soft',
                'plataformas' => ['Nintendo Wii', 'Nintendo 3DS', 'Nintendo Switch'],
                'generos' => ['JRPG', 'Action RPG', 'Open World'],
            ],
            [
                'nome' => 'Banjo-Kazooie',
                'desenvolvedora' => 'Rare',
                'plataformas' => ['Nintendo 64', 'Xbox 360'],
                'generos' => ['3D Platformer', 'Platformer'],
            ],
            [
                'nome' => 'GoldenEye 007',
                'desenvolvedora' => 'Rare',
                'plataformas' => ['Nintendo 64'],
                'generos' => ['First-Person Shooter'],
            ],
            [
                'nome' => 'Sea of Thieves',
                'desenvolvedora' => 'Rare',
                'plataformas' => ['PC', 'Xbox One', 'Xbox Series X/S', 'PlayStation 5'],
                'generos' => ['Adventure', 'Open World', 'Sandbox'],
            ],

            // ---------- FromSoftware ----------
            [
                'nome' => 'Demon\'s Souls',
                'desenvolvedora' => 'FromSoftware',
                'plataformas' => ['PlayStation 3'],
                'generos' => ['Action RPG', 'Dungeon Crawler'],
            ],
            [
                'nome' => 'Dark Souls',
                'desenvolvedora' => 'FromSoftware',
                'plataformas' => ['PlayStation 3', 'Xbox 360', 'PC'],
                'generos' => ['Action RPG', 'Action-Adventure'],
            ],
            [
                'nome' => 'Bloodborne',
                'desenvolvedora' => 'FromSoftware',
                'plataformas' => ['PlayStation 4'],
                'generos' => ['Action RPG', 'Action-Adventure'],
            ],
            [
                'nome' => 'Sekiro: Shadows Die Twice',
                'desenvolvedora' => 'FromSoftware',
                'plataformas' => ['PC', 'PlayStation 4', 'Xbox One'],
                'generos' => ['Action-Adventure', 'Action RPG'],
            ],
            [
                'nome' => 'Elden Ring',
                'desenvolvedora' => 'FromSoftware',
                'plataformas' => ['PC', 'PlayStation 4', 'PlayStation 5', 'Xbox One', 'Xbox Series X/S'],
                'generos' => ['Action RPG', 'Open World'],
            ],

            // ---------- PlayStation Studios ----------
            [
                'nome' => 'God of War',
                'desenvolvedora' => 'Santa Monica Studio',
                'plataformas' => ['PlayStation 4', 'PC'],
                'generos' => ['Action-Adventure', 'Hack and Slash'],
            ],
            [
                'nome' => 'God of War Ragnarök',
                'desenvolvedora' => 'Santa Monica Studio',
                'plataformas' => ['PlayStation 4', 'PlayStation 5', 'PC'],
                'generos' => ['Action-Adventure', 'Hack and Slash'],
            ],
            [
                'nome' => 'The Last of Us',
                'desenvolvedora' => 'Naughty Dog',
                'plataformas' => ['PlayStation 3', 'PlayStation 4'],
                'generos' => ['Action-Adventure', 'Survival Horror', 'Third-Person Shooter'],
            ],
            [
                'nome' => 'The Last of Us Part II',
                'desenvolvedora' => 'Naughty Dog',
                'plataformas' => ['PlayStation 4', 'PlayStation 5'],
                'generos' => ['Action-Adventure', 'Third-Person Shooter', 'Story-Rich'],
            ],
            [
                'nome' => 'Uncharted 4: A Thief\'s End',
                'desenvolvedora' => 'Naughty Dog',
                'plataformas' => ['PlayStation 4', 'PC'],
                'generos' => ['Action-Adventure', 'Third-Person Shooter'],
            ],
            [
                'nome' => 'Marvel\'s Spider-Man',
                'desenvolvedora' => 'Insomniac Games',
                'plataformas' => ['PlayStation 4', 'PlayStation 5', 'PC'],
                'generos' => ['Action-Adventure', 'Open World'],
            ],
            [
                'nome' => 'Ratchet & Clank: Rift Apart',
                'desenvolvedora' => 'Insomniac Games',
                'plataformas' => ['PlayStation 5', 'PC'],
                'generos' => ['3D Platformer', 'Action'],
            ],
            [
                'nome' => 'Horizon Zero Dawn',
                'desenvolvedora' => 'Guerrilla Games',
                'plataformas' => ['PlayStation 4', 'PC'],
                'generos' => ['Action RPG', 'Open World'],
            ],
            [
                'nome' => 'Ghost of Tsushima',
                'desenvolvedora' => 'Sucker Punch Productions',
                'plataformas' => ['PlayStation 4', 'PlayStation 5', 'PC'],
                'generos' => ['Action-Adventure', 'Open World'],
            ],
            [
                'nome' => 'LittleBigPlanet',
                'desenvolvedora' => 'Media Molecule',
                'plataformas' => ['PlayStation 3'],
                'generos' => ['Puzzle-Platformer', '2D Platformer'],
            ],
            [
                'nome' => 'Dreams',
                'desenvolvedora' => 'Media Molecule',
                'plataformas' => ['PlayStation 4'],
                'generos' => ['Sandbox', 'Simulation'],
            ],
            [
                'nome' => 'Journey',
                'desenvolvedora' => 'thatgamecompany',
                'plataformas' => ['PlayStation 3', 'PlayStation 4', 'PC'],
                'generos' => ['Adventure', 'Walking Simulator'],
            ],

            // ---------- Capcom / Konami / Square / Sega / Atlus ----------
            [
                'nome' => 'Resident Evil 4',
                'desenvolvedora' => 'Capcom',
                'plataformas' => ['Nintendo GameCube', 'PlayStation 2', 'PC', 'Nintendo Wii'],
                'generos' => ['Survival Horror', 'Third-Person Shooter', 'Action-Adventure'],
            ],
            [
                'nome' => 'Resident Evil Village',
                'desenvolvedora' => 'Capcom',
                'plataformas' => ['PC', 'PlayStation 4', 'PlayStation 5', 'Xbox One', 'Xbox Series X/S'],
                'generos' => ['Survival Horror', 'First-Person Shooter'],
            ],
            [
                'nome' => 'Monster Hunter: World',
                'desenvolvedora' => 'Capcom',
                'plataformas' => ['PlayStation 4', 'Xbox One', 'PC'],
                'generos' => ['Action RPG', 'Hack and Slash'],
            ],
            [
                'nome' => 'Devil May Cry 5',
                'desenvolvedora' => 'Capcom',
                'plataformas' => ['PC', 'PlayStation 4', 'PlayStation 5', 'Xbox One', 'Xbox Series X/S'],
                'generos' => ['Character Action', 'Hack and Slash', 'Action'],
            ],
            [
                'nome' => 'Street Fighter II',
                'desenvolvedora' => 'Capcom',
                'plataformas' => ['Super Nintendo', 'Sega Mega Drive'],
                'generos' => ['Fighting', '2D Fighting'],
            ],
            [
                'nome' => 'Metal Gear Solid',
                'desenvolvedora' => 'Konami',
                'plataformas' => ['PlayStation', 'PC'],
                'generos' => ['Stealth', 'Action-Adventure'],
            ],
            [
                'nome' => 'Silent Hill 2',
                'desenvolvedora' => 'Konami',
                'plataformas' => ['PlayStation 2', 'Xbox', 'PC'],
                'generos' => ['Survival Horror', 'Psychological Horror'],
            ],
            [
                'nome' => 'Castlevania: Symphony of the Night',
                'desenvolvedora' => 'Konami',
                'plataformas' => ['PlayStation', 'Sega Saturn'],
                'generos' => ['Metroidvania', 'Action-Adventure', '2D Platformer'],
            ],
            [
                'nome' => 'Final Fantasy VII',
                'desenvolvedora' => 'Square Enix',
                'plataformas' => ['PlayStation', 'PC'],
                'generos' => ['JRPG', 'RPG'],
            ],
            [
                'nome' => 'Final Fantasy XVI',
                'desenvolvedora' => 'Square Enix',
                'plataformas' => ['PlayStation 5', 'PC'],
                'generos' => ['Action RPG', 'JRPG'],
            ],
            [
                'nome' => 'Persona 5',
                'desenvolvedora' => 'Atlus',
                'plataformas' => ['PlayStation 3', 'PlayStation 4'],
                'generos' => ['JRPG', 'RPG', 'Turn-Based Strategy'],
            ],
            [
                'nome' => 'Sonic the Hedgehog',
                'desenvolvedora' => 'Sega',
                'plataformas' => ['Sega Mega Drive'],
                'generos' => ['2D Platformer', 'Platformer'],
            ],
            [
                'nome' => 'Yakuza 0',
                'desenvolvedora' => 'Sega',
                'plataformas' => ['PlayStation 3', 'PlayStation 4', 'PC', 'Xbox One'],
                'generos' => ['Action-Adventure', 'Beat \'em up'],
            ],
            [
                'nome' => 'Bayonetta',
                'desenvolvedora' => 'Platinum Games',
                'plataformas' => ['Xbox 360', 'PlayStation 3', 'Nintendo Switch', 'PC'],
                'generos' => ['Character Action', 'Hack and Slash'],
            ],
            [
                'nome' => 'NieR: Automata',
                'desenvolvedora' => 'Platinum Games',
                'plataformas' => ['PlayStation 4', 'PC', 'Xbox One', 'Nintendo Switch'],
                'generos' => ['Action RPG', 'Hack and Slash'],
            ],
            [
                'nome' => 'Tekken 7',
                'desenvolvedora' => 'Bandai Namco Studios',
                'plataformas' => ['PlayStation 4', 'Xbox One', 'PC'],
                'generos' => ['Fighting', '3D Fighting'],
            ],
            [
                'nome' => 'Guilty Gear Strive',
                'desenvolvedora' => 'Arc System Works',
                'plataformas' => ['PlayStation 4', 'PlayStation 5', 'PC', 'Xbox One', 'Xbox Series X/S'],
                'generos' => ['Fighting', '2D Fighting'],
            ],
            [
                'nome' => 'Dragon Ball FighterZ',
                'desenvolvedora' => 'Arc System Works',
                'plataformas' => ['PlayStation 4', 'Xbox One', 'PC', 'Nintendo Switch'],
                'generos' => ['Fighting', '2D Fighting'],
            ],
            [
                'nome' => 'Death Stranding',
                'desenvolvedora' => 'Kojima Productions',
                'plataformas' => ['PlayStation 4', 'PlayStation 5', 'PC'],
                'generos' => ['Action-Adventure', 'Walking Simulator', 'Open World'],
            ],

            // ---------- Ocidente AAA ----------
            [
                'nome' => 'The Witcher 3: Wild Hunt',
                'desenvolvedora' => 'CD Projekt Red',
                'plataformas' => ['PC', 'PlayStation 4', 'PlayStation 5', 'Xbox One', 'Xbox Series X/S', 'Nintendo Switch'],
                'generos' => ['Action RPG', 'Open World', 'WRPG'],
            ],
            [
                'nome' => 'Cyberpunk 2077',
                'desenvolvedora' => 'CD Projekt Red',
                'plataformas' => ['PC', 'PlayStation 4', 'PlayStation 5', 'Xbox One', 'Xbox Series X/S'],
                'generos' => ['Action RPG', 'Open World', 'First-Person Shooter'],
            ],
            [
                'nome' => "Baldur's Gate 3",
                'desenvolvedora' => 'Larian Studios',
                'plataformas' => ['PC', 'PlayStation 5', 'Xbox Series X/S'],
                'generos' => ['RPG', 'WRPG', 'Turn-Based Tactics'],
            ],
            [
                'nome' => 'Divinity: Original Sin 2',
                'desenvolvedora' => 'Larian Studios',
                'plataformas' => ['PC', 'PlayStation 4', 'Xbox One', 'Nintendo Switch'],
                'generos' => ['RPG', 'WRPG', 'Turn-Based Tactics'],
            ],
            [
                'nome' => 'The Elder Scrolls V: Skyrim',
                'desenvolvedora' => 'Bethesda Game Studios',
                'plataformas' => ['PC', 'PlayStation 3', 'Xbox 360', 'PlayStation 4', 'Xbox One', 'Nintendo Switch', 'PlayStation 5', 'Xbox Series X/S'],
                'generos' => ['Action RPG', 'Open World', 'WRPG'],
            ],
            [
                'nome' => 'Fallout 3',
                'desenvolvedora' => 'Bethesda Game Studios',
                'plataformas' => ['PC', 'PlayStation 3', 'Xbox 360'],
                'generos' => ['Action RPG', 'Open World', 'WRPG'],
            ],
            [
                'nome' => 'Fallout 4',
                'desenvolvedora' => 'Bethesda Game Studios',
                'plataformas' => ['PC', 'PlayStation 4', 'Xbox One', 'PlayStation 5', 'Xbox Series X/S'],
                'generos' => ['Action RPG', 'Open World', 'First-Person Shooter'],
            ],
            [
                'nome' => 'Fallout: New Vegas',
                'desenvolvedora' => 'Obsidian Entertainment',
                'plataformas' => ['PC', 'PlayStation 3', 'Xbox 360'],
                'generos' => ['Action RPG', 'Open World', 'WRPG'],
            ],
            [
                'nome' => 'Grand Theft Auto V',
                'desenvolvedora' => 'Rockstar Games',
                'plataformas' => ['PlayStation 3', 'Xbox 360', 'PC', 'PlayStation 4', 'Xbox One', 'PlayStation 5', 'Xbox Series X/S'],
                'generos' => ['Action-Adventure', 'Open World'],
            ],
            [
                'nome' => 'Red Dead Redemption 2',
                'desenvolvedora' => 'Rockstar Games',
                'plataformas' => ['PlayStation 4', 'Xbox One', 'PC'],
                'generos' => ['Action-Adventure', 'Open World'],
            ],
            [
                'nome' => 'Assassin\'s Creed II',
                'desenvolvedora' => 'Ubisoft Montreal',
                'plataformas' => ['PlayStation 3', 'Xbox 360', 'PC'],
                'generos' => ['Action-Adventure', 'Open World', 'Stealth'],
            ],
            [
                'nome' => 'Far Cry 3',
                'desenvolvedora' => 'Ubisoft Montreal',
                'plataformas' => ['PC', 'PlayStation 3', 'Xbox 360'],
                'generos' => ['First-Person Shooter', 'Open World'],
            ],
            [
                'nome' => 'Mass Effect 2',
                'desenvolvedora' => 'BioWare',
                'plataformas' => ['PC', 'Xbox 360', 'PlayStation 3'],
                'generos' => ['Action RPG', 'Third-Person Shooter', 'WRPG'],
            ],
            [
                'nome' => 'Dragon Age: Origins',
                'desenvolvedora' => 'BioWare',
                'plataformas' => ['PC', 'PlayStation 3', 'Xbox 360'],
                'generos' => ['RPG', 'WRPG'],
            ],
            [
                'nome' => 'Star Wars: Knights of the Old Republic',
                'desenvolvedora' => 'BioWare',
                'plataformas' => ['Xbox', 'PC'],
                'generos' => ['RPG', 'WRPG'],
            ],
            [
                'nome' => 'Tomb Raider',
                'desenvolvedora' => 'Crystal Dynamics',
                'plataformas' => ['PC', 'PlayStation 3', 'Xbox 360', 'PlayStation 4', 'Xbox One'],
                'generos' => ['Action-Adventure', 'Third-Person Shooter'],
            ],
            [
                'nome' => 'Just Cause 3',
                'desenvolvedora' => 'Avalanche Studios',
                'plataformas' => ['PC', 'PlayStation 4', 'Xbox One'],
                'generos' => ['Action-Adventure', 'Open World', 'Third-Person Shooter'],
            ],
            [
                'nome' => 'Dying Light',
                'desenvolvedora' => 'Techland',
                'plataformas' => ['PC', 'PlayStation 4', 'Xbox One'],
                'generos' => ['Survival Horror', 'Action-Adventure', 'Open World'],
            ],
            [
                'nome' => 'Life is Strange',
                'desenvolvedora' => 'Dontnod Entertainment',
                'plataformas' => ['PC', 'PlayStation 3', 'PlayStation 4', 'Xbox 360', 'Xbox One'],
                'generos' => ['Adventure', 'Interactive Drama', 'Story-Rich'],
            ],
            [
                'nome' => 'Psychonauts 2',
                'desenvolvedora' => 'Double Fine Productions',
                'plataformas' => ['PC', 'PlayStation 4', 'Xbox One', 'Xbox Series X/S'],
                'generos' => ['3D Platformer', 'Action-Adventure'],
            ],
            [
                'nome' => 'Forza Horizon 5',
                'desenvolvedora' => 'Playground Games',
                'plataformas' => ['PC', 'Xbox One', 'Xbox Series X/S'],
                'generos' => ['Racing', 'Arcade Racing', 'Open World'],
            ],

            // ---------- FPS / Shooters ----------
            [
                'nome' => 'DOOM',
                'desenvolvedora' => 'id Software',
                'plataformas' => ['PC', 'PlayStation 4', 'Xbox One', 'Nintendo Switch'],
                'generos' => ['First-Person Shooter', 'Action'],
            ],
            [
                'nome' => 'DOOM Eternal',
                'desenvolvedora' => 'id Software',
                'plataformas' => ['PC', 'PlayStation 4', 'Xbox One', 'Nintendo Switch', 'PlayStation 5', 'Xbox Series X/S'],
                'generos' => ['First-Person Shooter', 'Action'],
            ],
            [
                'nome' => 'Half-Life 2',
                'desenvolvedora' => 'Valve',
                'plataformas' => ['PC', 'Xbox', 'Xbox 360', 'PlayStation 3'],
                'generos' => ['First-Person Shooter'],
            ],
            [
                'nome' => 'Portal 2',
                'desenvolvedora' => 'Valve',
                'plataformas' => ['PC', 'PlayStation 3', 'Xbox 360'],
                'generos' => ['Puzzle-Platformer', 'Puzzle'],
            ],
            [
                'nome' => 'Halo: Combat Evolved',
                'desenvolvedora' => 'Bungie',
                'plataformas' => ['Xbox', 'PC'],
                'generos' => ['First-Person Shooter'],
            ],
            [
                'nome' => 'Destiny 2',
                'desenvolvedora' => 'Bungie',
                'plataformas' => ['PC', 'PlayStation 4', 'Xbox One', 'PlayStation 5', 'Xbox Series X/S'],
                'generos' => ['Looter Shooter', 'First-Person Shooter', 'MMO'],
            ],
            [
                'nome' => 'Halo Infinite',
                'desenvolvedora' => '343 Industries',
                'plataformas' => ['PC', 'Xbox One', 'Xbox Series X/S'],
                'generos' => ['First-Person Shooter'],
            ],
            [
                'nome' => 'Call of Duty 4: Modern Warfare',
                'desenvolvedora' => 'Infinity Ward',
                'plataformas' => ['PC', 'PlayStation 3', 'Xbox 360'],
                'generos' => ['First-Person Shooter'],
            ],
            [
                'nome' => 'Call of Duty: Black Ops',
                'desenvolvedora' => 'Treyarch',
                'plataformas' => ['PC', 'PlayStation 3', 'Xbox 360', 'Nintendo Wii', 'Nintendo DS'],
                'generos' => ['First-Person Shooter'],
            ],
            [
                'nome' => 'Battlefield 1',
                'desenvolvedora' => 'DICE',
                'plataformas' => ['PC', 'PlayStation 4', 'Xbox One'],
                'generos' => ['First-Person Shooter'],
            ],
            [
                'nome' => "Mirror's Edge",
                'desenvolvedora' => 'DICE',
                'plataformas' => ['PC', 'PlayStation 3', 'Xbox 360'],
                'generos' => ['Action-Adventure', 'Platformer'],
            ],
            [
                'nome' => 'Titanfall 2',
                'desenvolvedora' => 'Respawn Entertainment',
                'plataformas' => ['PC', 'PlayStation 4', 'Xbox One'],
                'generos' => ['First-Person Shooter'],
            ],
            [
                'nome' => 'Apex Legends',
                'desenvolvedora' => 'Respawn Entertainment',
                'plataformas' => ['PC', 'PlayStation 4', 'Xbox One', 'Nintendo Switch', 'PlayStation 5', 'Xbox Series X/S'],
                'generos' => ['Battle Royale', 'Hero Shooter', 'First-Person Shooter'],
            ],
            [
                'nome' => 'Star Wars Jedi: Fallen Order',
                'desenvolvedora' => 'Respawn Entertainment',
                'plataformas' => ['PC', 'PlayStation 4', 'Xbox One', 'PlayStation 5', 'Xbox Series X/S'],
                'generos' => ['Action-Adventure', 'Action RPG'],
            ],
            [
                'nome' => 'Wolfenstein: The New Order',
                'desenvolvedora' => 'MachineGames',
                'plataformas' => ['PC', 'PlayStation 3', 'PlayStation 4', 'Xbox 360', 'Xbox One'],
                'generos' => ['First-Person Shooter'],
            ],
            [
                'nome' => 'Dishonored',
                'desenvolvedora' => 'Arkane Studios',
                'plataformas' => ['PC', 'PlayStation 3', 'Xbox 360'],
                'generos' => ['Immersive Sim', 'Stealth', 'Action-Adventure'],
            ],
            [
                'nome' => 'Deathloop',
                'desenvolvedora' => 'Arkane Studios',
                'plataformas' => ['PC', 'PlayStation 5', 'Xbox Series X/S'],
                'generos' => ['First-Person Shooter', 'Immersive Sim'],
            ],
            [
                'nome' => 'Hitman',
                'desenvolvedora' => 'IO Interactive',
                'plataformas' => ['PC', 'PlayStation 4', 'Xbox One'],
                'generos' => ['Stealth', 'Action-Adventure'],
            ],
            [
                'nome' => 'Control',
                'desenvolvedora' => 'Remedy Entertainment',
                'plataformas' => ['PC', 'PlayStation 4', 'Xbox One', 'PlayStation 5', 'Xbox Series X/S'],
                'generos' => ['Action-Adventure', 'Third-Person Shooter'],
            ],
            [
                'nome' => 'Max Payne',
                'desenvolvedora' => 'Remedy Entertainment',
                'plataformas' => ['PC', 'PlayStation 2', 'Xbox'],
                'generos' => ['Third-Person Shooter', 'Action'],
            ],
            [
                'nome' => 'Alan Wake',
                'desenvolvedora' => 'Remedy Entertainment',
                'plataformas' => ['Xbox 360', 'PC'],
                'generos' => ['Action-Adventure', 'Psychological Horror'],
            ],
            [
                'nome' => 'Gears of War',
                'desenvolvedora' => 'Epic Games',
                'plataformas' => ['Xbox 360', 'PC'],
                'generos' => ['Third-Person Shooter'],
            ],
            [
                'nome' => 'Fortnite',
                'desenvolvedora' => 'Epic Games',
                'plataformas' => ['PC', 'PlayStation 4', 'Xbox One', 'Nintendo Switch', 'PlayStation 5', 'Xbox Series X/S'],
                'generos' => ['Battle Royale', 'Third-Person Shooter', 'Survival'],
            ],
            [
                'nome' => 'Borderlands 2',
                'desenvolvedora' => 'Gearbox Software',
                'plataformas' => ['PC', 'PlayStation 3', 'Xbox 360', 'PlayStation Vita', 'PlayStation 4', 'Xbox One', 'Nintendo Switch'],
                'generos' => ['Looter Shooter', 'First-Person Shooter', 'Action RPG'],
            ],

            // ---------- Estratégia / Blizzard / Riot ----------
            [
                'nome' => 'Diablo III',
                'desenvolvedora' => 'Blizzard Entertainment',
                'plataformas' => ['PC', 'PlayStation 3', 'Xbox 360', 'PlayStation 4', 'Xbox One', 'Nintendo Switch'],
                'generos' => ['Action RPG', 'Hack and Slash', 'Dungeon Crawler'],
            ],
            [
                'nome' => 'StarCraft II',
                'desenvolvedora' => 'Blizzard Entertainment',
                'plataformas' => ['PC'],
                'generos' => ['Real-Time Strategy'],
            ],
            [
                'nome' => 'Overwatch',
                'desenvolvedora' => 'Blizzard Entertainment',
                'plataformas' => ['PC', 'PlayStation 4', 'Xbox One', 'Nintendo Switch'],
                'generos' => ['Hero Shooter', 'First-Person Shooter'],
            ],
            [
                'nome' => 'League of Legends',
                'desenvolvedora' => 'Riot Games',
                'plataformas' => ['PC'],
                'generos' => ['MOBA'],
            ],
            [
                'nome' => 'Valorant',
                'desenvolvedora' => 'Riot Games',
                'plataformas' => ['PC'],
                'generos' => ['Tactical Shooter', 'First-Person Shooter', 'Hero Shooter'],
            ],
            [
                'nome' => 'XCOM: Enemy Unknown',
                'desenvolvedora' => 'Firaxis Games',
                'plataformas' => ['PC', 'PlayStation 3', 'Xbox 360'],
                'generos' => ['Turn-Based Tactics', 'Strategy'],
            ],
            [
                'nome' => 'Civilization VI',
                'desenvolvedora' => 'Firaxis Games',
                'plataformas' => ['PC', 'Nintendo Switch', 'PlayStation 4', 'Xbox One'],
                'generos' => ['4X', 'Turn-Based Strategy'],
            ],
            [
                'nome' => 'Total War: Shogun 2',
                'desenvolvedora' => 'Creative Assembly',
                'plataformas' => ['PC'],
                'generos' => ['Real-Time Strategy', 'Turn-Based Strategy', 'Grand Strategy'],
            ],

            // ---------- Indies ----------
            [
                'nome' => 'Hollow Knight',
                'desenvolvedora' => 'Team Cherry',
                'plataformas' => ['PC', 'Nintendo Switch', 'PlayStation 4', 'Xbox One'],
                'generos' => ['Metroidvania', 'Action-Adventure', '2D Platformer'],
            ],
            [
                'nome' => 'Stardew Valley',
                'desenvolvedora' => 'ConcernedApe',
                'plataformas' => ['PC', 'PlayStation 4', 'Xbox One', 'Nintendo Switch', 'PlayStation Vita'],
                'generos' => ['Farming Simulation', 'Life Simulation', 'RPG'],
            ],
            [
                'nome' => 'Cuphead',
                'desenvolvedora' => 'Studio MDHR',
                'plataformas' => ['PC', 'Xbox One', 'Nintendo Switch', 'PlayStation 4'],
                'generos' => ['Run and Gun', '2D Platformer', 'Bullet Hell'],
            ],
            [
                'nome' => 'Hades',
                'desenvolvedora' => 'Supergiant Games',
                'plataformas' => ['PC', 'Nintendo Switch', 'PlayStation 4', 'PlayStation 5', 'Xbox One', 'Xbox Series X/S'],
                'generos' => ['Roguelike', 'Action RPG', 'Hack and Slash'],
            ],
            [
                'nome' => 'Bastion',
                'desenvolvedora' => 'Supergiant Games',
                'plataformas' => ['PC', 'Xbox 360', 'PlayStation 4', 'Nintendo Switch'],
                'generos' => ['Action RPG'],
            ],
            [
                'nome' => 'Terraria',
                'desenvolvedora' => 'Re-Logic',
                'plataformas' => ['PC', 'Xbox 360', 'PlayStation 3', 'PlayStation 4', 'Xbox One', 'Nintendo Switch', 'Nintendo 3DS', 'PlayStation Vita'],
                'generos' => ['Sandbox', 'Survival Crafting', '2D Platformer'],
            ],
            [
                'nome' => 'Minecraft',
                'desenvolvedora' => 'Mojang Studios',
                'plataformas' => ['PC', 'Xbox 360', 'PlayStation 3', 'PlayStation 4', 'Xbox One', 'Nintendo Switch', 'Wii U', 'Nintendo 3DS', 'PlayStation Vita'],
                'generos' => ['Sandbox', 'Survival Crafting', 'Open World'],
            ],
            [
                'nome' => 'Among Us',
                'desenvolvedora' => 'Innersloth',
                'plataformas' => ['PC', 'Nintendo Switch', 'PlayStation 4', 'PlayStation 5', 'Xbox One', 'Xbox Series X/S'],
                'generos' => ['Party', 'Casual'],
            ],
            [
                'nome' => 'Ori and the Blind Forest',
                'desenvolvedora' => 'Moon Studios',
                'plataformas' => ['PC', 'Xbox 360', 'Xbox One', 'Nintendo Switch'],
                'generos' => ['Metroidvania', '2D Platformer'],
            ],
            [
                'nome' => 'Limbo',
                'desenvolvedora' => 'Playdead',
                'plataformas' => ['Xbox 360', 'PlayStation 3', 'PC', 'PlayStation 4', 'Xbox One', 'Nintendo Switch', 'PlayStation Vita'],
                'generos' => ['Puzzle-Platformer', '2D Platformer'],
            ],
            [
                'nome' => 'Inside',
                'desenvolvedora' => 'Playdead',
                'plataformas' => ['PC', 'Xbox One', 'PlayStation 4', 'Nintendo Switch'],
                'generos' => ['Puzzle-Platformer', '2D Platformer'],
            ],
            [
                'nome' => 'It Takes Two',
                'desenvolvedora' => 'Hazelight Studios',
                'plataformas' => ['PC', 'PlayStation 4', 'PlayStation 5', 'Xbox One', 'Xbox Series X/S', 'Nintendo Switch'],
                'generos' => ['Action-Adventure', 'Puzzle-Platformer', 'Party'],
            ],
            [
                'nome' => "Don't Starve",
                'desenvolvedora' => 'Klei Entertainment',
                'plataformas' => ['PC', 'PlayStation 4', 'Xbox One', 'Nintendo Switch', 'PlayStation Vita'],
                'generos' => ['Survival', 'Roguelike'],
            ],
            [
                'nome' => 'Mark of the Ninja',
                'desenvolvedora' => 'Klei Entertainment',
                'plataformas' => ['PC', 'Xbox 360', 'Nintendo Switch', 'PlayStation 4'],
                'generos' => ['Stealth', '2D Platformer'],
            ],
            [
                'nome' => 'Satisfactory',
                'desenvolvedora' => 'Coffee Stain Studios',
                'plataformas' => ['PC'],
                'generos' => ['Base Building', 'Simulation', 'Survival Crafting'],
            ],
            [
                'nome' => 'Genshin Impact',
                'desenvolvedora' => 'miHoYo',
                'plataformas' => ['PC', 'PlayStation 4', 'PlayStation 5'],
                'generos' => ['Action RPG', 'Open World'],
            ],
            [
                'nome' => 'No More Heroes',
                'desenvolvedora' => 'Grasshopper Manufacture',
                'plataformas' => ['Nintendo Wii', 'Nintendo Switch', 'PC'],
                'generos' => ['Hack and Slash', 'Action-Adventure'],
            ],
            [
                'nome' => 'Star Ocean: Till the End of Time',
                'desenvolvedora' => 'tri-Ace',
                'plataformas' => ['PlayStation 2'],
                'generos' => ['JRPG', 'Action RPG'],
            ],
        ];
    }
}
