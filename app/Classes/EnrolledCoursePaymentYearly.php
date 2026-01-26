<?php

namespace App\Classes;

use App\Models\EnrolledCoursePayment;
use Illuminate\Support\Facades\Cache;

class EnrolledCoursePaymentYearly
{
    /**
     * Get annual payments grouped by month for a given year
     * Uses caching to avoid repeated DB queries
     *
     * @param int $year
     * @param int $ttl Time to live in minutes
     * @return \Illuminate\Support\Collection
     */
    public static function get($year, $ttl = 1)
    {
        $cacheKey = "enrolled_course_payments_yearly_{$year}";

        return Cache::remember($cacheKey, $ttl, function () use ($year) {
            return EnrolledCoursePayment::selectRaw('MONTH(payment_date) as month, SUM(paid_amount) as total')
                ->whereYear('payment_date', $year)
                ->where('is_deleted', 0)
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        });
    }

    /**
     * Clear the cache for a given year
     *
     * @param int $year
     * @return void
     */
    public static function clear($year)
    {
        $cacheKey = "enrolled_course_payments_yearly_{$year}";
        Cache::forget($cacheKey);
    }
}
