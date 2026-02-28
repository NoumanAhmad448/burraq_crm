<?php

use App\Http\Controllers\Admin\Access\UserRoleController;
use App\Http\Controllers\UsrController;
use App\Http\Controllers\Admin\Role\RoleController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'middleware' => [config('middlewares.auth'), config('middlewares.admin')]], function () {
    Route::get('users', [UsrController::class, 'index'])->name('admin.user.index');
    Route::get('users/create', [UsrController::class, 'create'])->name('admin.user.create');
    Route::post('users', [UsrController::class, 'store'])->name('admin.user.store');
    Route::get('users/{user}/edit', [UsrController::class, 'edit'])->name('admin.user.edit');
    Route::put('users/{user}', [UsrController::class, 'update'])->name('admin.user.update');
    Route::delete('users/{user}', [UsrController::class, 'destroy'])->name('admin.user.destroy');

    // Logs
    Route::get('users/logs', [UsrController::class, 'logs'])->name('admin.user.logs');
});

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('roles', [RoleController::class, 'store'])->name('roles.store');

    Route::get('users/assign-roles', [UserRoleController::class, 'index'])->name('users.assign.roles');
    Route::post('users/assign-roles', [UserRoleController::class, 'store'])->name('users.assign.roles.store');
});