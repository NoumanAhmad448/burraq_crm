<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    public function boot()
    {
        

        \App\Macros\CommonMacros::register();
        \App\Macros\EnrolledCourseScope::register();
        \App\Macros\EnrolledCourseMacros::register();
        \App\Macros\EnrolledCourseFilterMacros::register();
        \App\Macros\Paymentmacros::register();


    }
}