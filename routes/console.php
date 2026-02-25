<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('sanciones:actualizar')->weeklyOn(0, '23:59'); // lunes 00:00

//Schedule::command('sanciones:actualizar')->everyMinute(); // PARA PRUEBAS SOLO, LUEGO COMENTAR O ELIMINAR
