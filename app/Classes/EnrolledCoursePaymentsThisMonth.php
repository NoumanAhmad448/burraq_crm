<?php

namespace App\Classes;

use App\Models\EnrolledCoursePayment;
use Illuminate\Support\Facades\Cache;

class EnrolledCoursePaymentsThisMonth
{
    /**
     * Get total paid amount for this month
     * TTL = 1 minute
     *
     * @param string $startOfMonth
     * @param string $endOfMonth
     * @param int $ttl
     * @return float
     */
    public static function get($startOfMonth, $endOfMonth, $ttl = 1)
    {
        $cacheKey = "enrolled_course_payments_this_month_{$startOfMonth}_{$endOfMonth}";

        return Cache::remember($cacheKey, $ttl, function () use ($startOfMonth, $endOfMonth) {

            $paymentsThisMonth = EnrolledCoursePayment::query()
                ->whereBetween('payment_date', [$startOfMonth, $endOfMonth])
                ->where('is_deleted', 0) // payment active
                ->whereHas('enrolledCourse', function ($q) {
                    $q->where('is_deleted', 0); // course active
                })
                ->whereHas('enrolledCourse.student', function ($q) {
                    $q->where('is_deleted', 0); // student active
                })
                ->sum('paid_amount');

            return $paymentsThisMonth;
        });
    }

    /**
     * Clear cache
     *
     * @param string $startOfMonth
     * @param string $endOfMonth
     * @return void
     */
    public static function clear($startOfMonth, $endOfMonth)
    {
        Cache::forget("enrolled_course_payments_this_month_{$startOfMonth}_{$endOfMonth}");
    }
}
