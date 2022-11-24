<?php

use Illuminate\Foundation\Inspiring;

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
})->describe('Display an inspiring quote');


Route::get('/cache-clear', function () {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

Route::get('/route-clear', function () {
    Artisan::call('route:clear');
    return "Route is cleared";
});

Route::get('/config-clear', function () {
    Artisan::call('config:clear');
    return "Config is cleared";
});

Route::get('/view-clear', function () {
    Artisan::call('view:clear');
    return "View is cleared";
});
