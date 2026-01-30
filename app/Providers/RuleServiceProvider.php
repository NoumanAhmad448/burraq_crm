<?php

namespace App\Providers;

use App\Models\Student;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Email;
use Illuminate\Support\Facades\Gate;


class RuleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::define('is-deleted-student', function ($user, Student $student) {
            return (int) $student->is_deleted == 1;
        });

        Gate::define('is-super-admin', function () {
            return auth()->user()->isSuperAdmin();
        });

        Gate::define('is-admin', function ($user, Student $student) {
            return auth()->user()->isAdmin();
        });
    }
}
