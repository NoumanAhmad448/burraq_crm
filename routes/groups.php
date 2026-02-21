<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\GroupModuleController;
use App\Http\Controllers\EnrollmentController;

Route::group(['middleware' => config('middlewares.auth')], function () {

    Route::get('groups', [GroupController::class, 'index'])->name('admin.groups.index');
    Route::post('groups/assign-instructor', [EnrollmentController::class, 'assignInstructor'])->name('admin.group.assign_instructor');
    Route::post('groups/assign-student', [EnrollmentController::class, 'assignStudent'])->name('admin.group.assign_student');
    Route::get('groups/{group}/assiged-student', [EnrollmentController::class, 'students'])    ->name('admin.group.students');
    Route::delete('admin/groups/{group}/students/{student}', 
        [EnrollmentController::class, 'removeStudent']
    )->name('admin.groups.students.destroy');

    // Route::post('groups/{group}/students/update', [EnrollmentController::class, 'updateStudents'])
    //     ->name('admin.group.students.update');

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
    Route::get('group/{group}/modules', [GroupModuleController::class, 'index'])
        ->name('admin.group.modules');

    Route::post('{group}/modules', [GroupModuleController::class, 'store'])
        ->name('admin.group.modules.store');

    Route::put('{group}/modules/{module}', [GroupModuleController::class, 'update'])
        ->name('admin.group.modules.update');

    Route::delete('{group}/modules/{module}', [GroupModuleController::class, 'destroy'])
        ->name('admin.group.modules.destroy');

    Route::get('admin/{group_id}logs/groups', [ActivityLogController::class, 'groupLogs'])
    ->name('admin.logs.groups');

    Route::get('admin/logs/modules/{module_id}', [ActivityLogController::class, 'moduleLogs'])
        ->name('admin.logs.modules');

    Route::get('admin/logs/group-enrollments/{id}', [ActivityLogController::class, 'groupEnrollmentLogs'])
        ->name('admin.logs.group_enrollments');
});

