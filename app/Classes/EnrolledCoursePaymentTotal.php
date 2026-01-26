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

        return Cache::remember($cacheKey, $ttl, function () {
            $totalPaid_g = EnrolledCoursePayment::query()->where('is_deleted', 0) // payment active
                ->whereHas('enrolledCourse', function ($q) {
                    $q->where('is_deleted', 0); // course active
                })
                ->whereHas('enrolledCourse.student', function ($q) {
                    $q->where('is_deleted', 0); // student active
                })
                ->sum('paid_amount');

            return $totalPaid_g;
        });
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
