<?php

namespace App\Providers;

use App\Macros\UserMacros;
use Illuminate\Support\ServiceProvider;
use App\Macros\CommonMacros;
use App\Macros\EnrolledCourseScope;
use App\Macros\EnrolledCourseMacros;
use App\Macros\EnrolledCourseFilterMacros;
use App\Macros\PaymentMacros;

class MacroServiceProvider extends ServiceProvider
{
    public function boot()
    {

        CommonMacros::register();
        EnrolledCourseScope::register();
        EnrolledCourseMacros::register();
        EnrolledCourseFilterMacros::register();
        PaymentMacros::register();
        UserMacros::register();
    }
}
