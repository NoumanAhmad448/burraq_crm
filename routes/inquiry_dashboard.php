<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InquiryDashboardController;

Route::middleware(config("middlewares.auth"))->group(function () {

    Route::get('inquiry-dashboard', [InquiryDashboardController::class, 'dashboard'])
        ->name('inquiry_dashboard.index');

});
