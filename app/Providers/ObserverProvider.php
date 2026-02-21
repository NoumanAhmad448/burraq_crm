<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Student as CrmStudent;
use App\Observers\CrmStudentObserver;
use App\Models\EnrolledCourse;
use App\Observers\EnrolledCourseObserver;
use App\Models\Inquiry;
use App\Models\Profile;
use App\Models\User;
use App\Observers\InquiryObserver;
use App\Observers\ProfileObserver;
use App\Observers\UserObserver;
use App\Models\EnrolledCoursePayment;
use App\Observers\EnrolledCoursePaymentObserver;
use App\Models\Group;
use App\Models\GroupCourseProgress;
use App\Models\CrmEnrolledCourse;
use App\Models\GroupEnrollment;
use App\Observers\GroupObserver;
use App\Observers\GroupCourseProgressObserver;
use App\Observers\GroupEnrollmentObserver;


class ObserverProvider extends ServiceProvider
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
        // Factory::factoryForModel(CronJobs::class, CronJobsFactory::class);
        CrmStudent::observe(CrmStudentObserver::class);
        EnrolledCourse::observe(EnrolledCourseObserver::class);
        Inquiry::observe(InquiryObserver::class);
        Profile::observe(ProfileObserver::class);
        User::observe(UserObserver::class);
        EnrolledCoursePayment::observe(EnrolledCoursePaymentObserver::class);
        Group::observe(GroupObserver::class);
        GroupCourseProgress::observe(GroupCourseProgressObserver::class);
        GroupEnrollment::observe(GroupEnrollmentObserver::class);
    }
}
