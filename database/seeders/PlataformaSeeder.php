<?php

namespace Database\Seeders;

use App\Models\Catalogo\Plataforma;
use Illuminate\Database\Seeder;

class PlataformaSeeder extends Seeder
{
    public function run(): void
    {
        $plataformas = [
            ['nome' => 'Atari 2600',            'lancamento' => '1977-09-11'],
            ['nome' => 'Nintendo Entertainment System', 'lancamento' => '1983-07-15'],
            ['nome' => 'Sega Master System',    'lancamento' => '1985-10-20'],
            ['nome' => 'Game Boy',              'lancamento' => '1989-04-21'],
            ['nome' => 'Sega Mega Drive',       'lancamento' => '1988-10-29'],
            ['nome' => 'Super Nintendo',        'lancamento' => '1990-11-21'],
            ['nome' => 'Sega Saturn',           'lancamento' => '1994-11-22'],
            ['nome' => 'PlayStation',           'lancamento' => '1994-12-03'],
            ['nome' => 'Nintendo 64',           'lancamento' => '1996-06-23'],
            ['nome' => 'Game Boy Color',        'lancamento' => '1998-10-21'],
            ['nome' => 'Sega Dreamcast',        'lancamento' => '1998-11-27'],
            ['nome' => 'PlayStation 2',         'lancamento' => '2000-03-04'],
            ['nome' => 'Game Boy Advance',      'lancamento' => '2001-03-21'],
            ['nome' => 'Nintendo GameCube',     'lancamento' => '2001-09-14'],
            ['nome' => 'Xbox',                  'lancamento' => '2001-11-15'],
            ['nome' => 'Nintendo DS',           'lancamento' => '2004-11-21'],
            ['nome' => 'PlayStation Portable',  'lancamento' => '2004-12-12'],
            ['nome' => 'Xbox 360',              'lancamento' => '2005-11-22'],
            ['nome' => 'PlayStation 3',         'lancamento' => '2006-11-11'],
            ['nome' => 'Nintendo Wii',          'lancamento' => '2006-11-19'],
            ['nome' => 'Nintendo 3DS',          'lancamento' => '2011-02-26'],
            ['nome' => 'PlayStation Vita',      'lancamento' => '2011-12-17'],
            ['nome' => 'Wii U',                 'lancamento' => '2012-11-18'],
            ['nome' => 'PlayStation 4',         'lancamento' => '2013-11-15'],
            ['nome' => 'Xbox One',              'lancamento' => '2013-11-22'],
            ['nome' => 'Nintendo Switch',       'lancamento' => '2017-03-03'],
            ['nome' => 'Xbox Series X/S',       'lancamento' => '2020-11-10'],
            ['nome' => 'PlayStation 5',         'lancamento' => '2020-11-12'],
            ['nome' => 'PC',                    'lancamento' => null],
        ];

        foreach ($plataformas as $dados) {
            Plataforma::firstOrCreate(
                ['nome' => $dados['nome']],
                ['lancamento' => $dados['lancamento']],
            );
        }
    }
}
