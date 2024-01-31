<?php

use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/** @var TelegraphBot $telegraphBot */
Artisan::command('make:menu', function () {
    $telegraphBot = TelegraphBot::find(1);

    $telegraphBot->registerCommands([
        'start' => 'for start0',
        'score' => 'get score',
        'info' => 'get info',
    ])->send();
});
