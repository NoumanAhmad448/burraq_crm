<?php

use App\Http\Controllers\Admin\StudentCourseController;
use Illuminate\Support\Facades\Route;

Route::middleware(config('middlewares.auth'))->group(function () {
    Route::get(
        'student/course/{enrolledCourse}',
        [StudentCourseController::class, 'show']
    )->name('student.course.detail');
});
