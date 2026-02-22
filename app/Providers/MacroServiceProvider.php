<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Macros\CommonMacros;
use App\Macros\EnrolledCourseScope;
use App\Macros\EnrolledCourseMacros;
use App\Macros\EnrolledCourseFilterMacros;
use App\Macros\Paymentmacros;

class MacroServiceProvider extends ServiceProvider
{
    public function boot()
    {
        

        CommonMacros::register();
        EnrolledCourseScope::register();
        EnrolledCourseMacros::register();
        EnrolledCourseFilterMacros::register();
        Paymentmacros::register();


    }
}