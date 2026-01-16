<?php

use App\Http\Controllers\HrController;
use Illuminate\Support\Facades\Route;

Route::middleware([config('middlewares.auth')])->group(function () {

    Route::get('hr', [HrController::class, 'index'])->name('hr.index');
    Route::post('hr/store', [HrController::class, 'store'])->name('hr.store');
    Route::get('hr/{user}/edit', [HrController::class, 'edit'])->name('hr.edit');
    Route::post('hr/{user}/update', [HrController::class, 'update'])->name('hr.update');
    Route::get('hr/logs', [HrController::class, 'logs'])->name('hr.logs');
    Route::get('hr/{user}/destroy', [HrController::class, 'destroy'])->name('hr.destroy');

});
