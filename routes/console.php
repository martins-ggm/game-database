<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
| Manutenção do catálogo. Depois do backfill inicial, cada execução traz só
| o que o IGDB alterou desde o cursor — normalmente poucos lotes.
|
| withoutOverlapping porque uma execução longa não pode ser atropelada pela
| seguinte: as duas partiriam do mesmo cursor e reprocessariam o mesmo trecho.
*/
Schedule::command('igdb:sincronizar --tudo')
    ->hourly()
    ->withoutOverlapping()
    ->runInBackground();
