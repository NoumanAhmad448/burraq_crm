<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InquiryDashboardController;
use App\Http\Controllers\Admin\InquiryDashboardController as IDC;

Route::middleware(config("middlewares.auth"))->group(function () {

    Route::get('inquiry-dashboard', [InquiryDashboardController::class, 'dashboard'])
        ->name('inquiry_dashboard.index');

    Route::get('course-dashboard', [IDC::class, 'index'])
        ->name('course_dashboard.index');
});
