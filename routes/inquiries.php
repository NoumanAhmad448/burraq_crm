<?php

use App\Http\Controllers\Admin\InquiryController;
use Illuminate\Support\Facades\Route;

Route::middleware(config('middlewares.auth'))->group(function () {

    Route::get('inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
    Route::get('inquiries/create', [InquiryController::class, 'create'])->name('inquiries.create');
    Route::post('inquiries/store', [InquiryController::class, 'store'])->name('inquiries.store');

    Route::get('inquiries/edit/{id}', [InquiryController::class, 'edit'])->name('inquiries.edit');
    Route::post('inquiries/update/{id}', [InquiryController::class, 'update'])->name('inquiries.update');

    Route::post('inquiries/delete/{id}', [InquiryController::class, 'delete'])->name('inquiries.delete');
    Route::get('inquiries/logs/{id}', [InquiryController::class, 'logs'])
    ->name('inquiries.logs');

});
