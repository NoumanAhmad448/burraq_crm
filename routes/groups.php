<?php

use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\GroupModuleController;

Route::group(['middleware' => config('middlewares.auth')], function () {

    Route::get('groups', [GroupController::class, 'index'])->name('admin.groups.index');
    Route::post('groups/{group}/assign-instructor', [GroupController::class, 'assignInstructor'])->name('admin.group.assign_instructor');
    Route::post('groups/{group}/assign-student', [GroupController::class, 'assignStudent'])->name('admin.group.assign_student');

    Route::get('groups/create', [GroupController::class, 'create'])->name('admin.groups.create');
    Route::post('groups', [GroupController::class, 'store'])->name('admin.groups.store');
    Route::get('groups/{group}/edit', [GroupController::class, 'edit'])->name('admin.groups.edit');
    Route::put('groups/{group}', [GroupController::class, 'update'])->name('admin.groups.update');
    Route::delete('groups/{group}', [GroupController::class, 'destroy'])->name('admin.groups.destroy');

    Route::get('groups/trashed', [GroupController::class, 'trashed'])
        ->name('admin.groups.trashed');

    Route::put('groups/{group}/restore', [GroupController::class, 'restore'])
        ->name('admin.groups.restore');

    // Modules for a specific group
    Route::get('{group}/modules', [GroupModuleController::class, 'index'])
        ->name('admin.group.modules');

    Route::post('{group}/modules', [GroupModuleController::class, 'store'])
        ->name('admin.group.modules.store');

    Route::put('{group}/modules/{module}', [GroupModuleController::class, 'update'])
        ->name('admin.group.modules.update');

    Route::delete('{group}/modules/{module}', [GroupModuleController::class, 'destroy'])
        ->name('admin.group.modules.destroy');
});
