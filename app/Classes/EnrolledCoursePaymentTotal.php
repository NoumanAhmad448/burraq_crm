<?php

namespace App\Classes;

use App\Models\EnrolledCoursePayment;
use Illuminate\Support\Facades\Cache;

class EnrolledCoursePaymentTotal
{
    /**
     * Get total paid amount for all active payments, courses, and students
     * TTL = 1 minute
     *
     * @param int $ttl Time to live in minutes
     * @return float
     */
    public static function get($ttl = 1)
    {
        $cacheKey = "enrolled_course_payment_total";

        // return Cache::remember($cacheKey, $ttl, function () {
            $totalPaid_g = EnrolledCoursePayment::query()
                ->active()
                ->whereHas('enrolledCourse', function ($q) {
                    $q->active()->activeStatus();
                })
                ->whereHas('enrolledCourse.student', function ($q) {
                    $q->active();
                })
                ->totalPaid()
                ;


            return $totalPaid_g;
        // });
    }

    /**
     * Clear cached total paid
     *
     * @return void
     */
    public static function clear()
    {
        Cache::forget("enrolled_course_payment_total");
    }
}
