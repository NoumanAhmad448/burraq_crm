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
                ->where('is_deleted', 0)
                ->whereHas('enrolledCourse', function ($q) {
                    $q->where('is_deleted', 0);
                })
                ->whereHas('enrolledCourse.student', function ($q) {
                    $q->where('is_deleted', 0);
                })
                ->selectRaw("
                    COALESCE(
                        SUM(
                            CASE 
                                WHEN type = 'refunded' THEN -paid_amount
                                ELSE paid_amount
                            END
                        ), 0
                    ) as net_total
                ")
                ->value('net_total');


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
