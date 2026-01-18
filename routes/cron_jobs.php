<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CronJobController;

Route::group([
    'middleware' => [config('middlewares.auth'), 'super_admin'],
    'prefix' => 'cron-jobs'
], function () {
    Route::get('/', [CronJobController::class, 'index'])->name('cron-jobs.index');
});
