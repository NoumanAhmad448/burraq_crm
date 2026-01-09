<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StudentController;

Route::middleware(config('middlewares.auth'))->group(function () {

    // Students
    Route::get('students', [StudentController::class, 'index'])
        ->name('students.index');

    Route::post('students/store', [StudentController::class, 'store'])
        ->name('students.store');

    Route::get('students/{id}', [StudentController::class, 'show'])
        ->name('students.show');

    Route::get('students/{id}/edit', [StudentController::class, 'edit'])
        ->name('students.edit');

    Route::post('students/{id}/update', [StudentController::class, 'update'])
        ->name('students.update');

    Route::delete('students/{id}', [StudentController::class, 'delete'])
        ->name('students.delete');

    // Print / Receipt
    Route::get('students/{id}/print', [StudentController::class, 'print'])
        ->name('students.print');

    Route::get('students/{id}/receipt', [StudentController::class, 'receipt'])
        ->name('students.receipt');
    Route::get(
        'students/{studentId}/course/{enrolledCourseId}',
        [StudentController::class, 'courseDetail']
    )->name('students.course.detail');
    Route::get(
        'enrolled-course/{enrolledCourseId}/payment/create',
        [StudentController::class, 'createPayment']
    )->name('enrolled.course.payment.create');

    Route::post(
        'enrolled-course/payment/store',
        [StudentController::class, 'storePayment']
    )->name('enrolled.course.payment.store');
});
