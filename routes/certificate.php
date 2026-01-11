<?php

use App\Http\Controllers\CertificateController;
use Illuminate\Support\Facades\Route;

Route::middleware(config('middlewares.auth'))->group(function () {

    /**
     * Global listing
     * ALL students + ALL enrolled courses
     */
    Route::get(
        'certificates',
        [CertificateController::class, 'index']
    )->name('certificates.index');

    /**
     * Student + Enrolled Course certificate page
     * (detail view / generate)
     */
    Route::get(
        'students/{student}/certificate',
        [CertificateController::class, 'show']
    )->name('students.certificate');

    /**
     * Generate certificate
     */
    Route::get(
        'students/{student}/{enrolledCourseId}/certificate/generate',
        [CertificateController::class, 'generate']
    )->name('students.certificate.generate');

    /**
     * Certificate logs
     */
    Route::get(
        'students/Enrolledcourse/{enrolledCourseId}/certificate/logs',
        [CertificateController::class, 'logs']
    )->name('students.certificate.logs');

});
