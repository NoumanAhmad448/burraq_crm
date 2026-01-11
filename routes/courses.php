<?php

use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\EnrolledCoursePaymentController;
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



Route::group(['middleware' => config('middlewares.auth')], function() {
Route::get('students/{student_id}/courses/{enrolledCourseId}/payments',
    [EnrolledCoursePaymentController::class, 'paymentsList'])
    ->name('students.course.payments');
    Route::post('course-payments/store', [EnrolledCoursePaymentController::class, 'store'])->name('course_payments.store');

    // Update Payment (no form, just via route + request)
    Route::post('course-payments/{payment}/update', [EnrolledCoursePaymentController::class, 'update'])->name('course_payments.update');
    Route::get('course-payments/{student_id}/courses/{enrolledCourseId}/{payment_id}/update', [EnrolledCoursePaymentController::class, 'get'])->name('course_payments.get');

    // Soft Delete Payment
    Route::post('course-payments/{payment}/delete', [EnrolledCoursePaymentController::class, 'destroy'])->name('course_payments.delete');
});

