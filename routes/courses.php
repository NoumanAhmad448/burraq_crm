<?php

use App\Http\Controllers\Admin\CourseController;
use Illuminate\Support\Facades\Route;


Route::group([
    'middleware' => config('middlewares.auth'),
    'prefix' => 'courses'
], function () {

    Route::get('/', [CourseController::class, 'index'])->name('courses.index');
    Route::post('/store', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/edit/{id}', [CourseController::class, 'edit'])->name('courses.edit');
    Route::post('/update/{id}', [CourseController::class, 'update'])->name('courses.update');
    Route::post('/delete/{id}', [CourseController::class, 'delete'])->name('courses.delete');

});
