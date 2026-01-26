<?php

namespace App\Providers;

use App\Http\Contracts\InquiryDashboardContract;
use App\Http\Response\InquiryDashboardResponse;
use App\Models\ConfigSetting;
use App\Models\Social;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (trim(config('app.env')) == config("setting.roles.dev")) {
            $this->app['request']->server->set('HTTP', true);
            resolve(\Illuminate\Routing\UrlGenerator::class)->forceScheme(config("setting.http"));
            URL::forceScheme(config("setting.http"));
        }
        $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
        $this->app->register(TelescopeServiceProvider::class);
        $this->app->bind(
            InquiryDashboardContract::class,
            InquiryDashboardResponse::class
        );
    }

    /**
     * Bootstrap any application services.
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


        try {
            DB::connection()->getPdo();  // Try to connect to the database
            if (Schema::hasTable('socials')) {
                $social = Social::first();
                if ($social) {
                    $social->setSocialMedia();
                }
            }

            if (Schema::hasTable('config_settings')) {
                $settings = ConfigSetting::all();
                if ($settings) {
                    foreach ($settings as $setting) {
                        config(["setting." . $setting->key => false]);
                    }
                }
            }
            if (trim(config('app.env')) == config("setting.roles.dev")) {
                URL::forceScheme(config("setting.http"));
                resolve(\Illuminate\Routing\UrlGenerator::class)->forceScheme(config("setting.http"));
            }
        } catch (\Exception $e) {
            Log::error('Database connection failed: ' . $e->getMessage());
        }
    }
}
