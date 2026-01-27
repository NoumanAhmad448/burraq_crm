<?php

namespace App\Classes;

use App\Models\EnrolledCourse;
use Illuminate\Support\Facades\Cache;

class EnrolledCourseDue
{
    /**
     * Get total due amount for courses in a given month and year
     * Uses caching to avoid repeated DB queries
     *
     * @param int $month
     * @param int $year
     * @param string|null $startOfMonth
     * @param string|null $endOfMonth
     * @param int $ttl Time to live in minutes
     * @return float
     */
    public static function get($month, $year, $startOfMonth = null, $endOfMonth = null, $ttl = 1)
    {
        // Generate cache key including month and year
        $cacheKey = "enrolled_course_due_{$year}_{$month}";

        return Cache::remember($cacheKey, $ttl, function () use ($month, $year, $startOfMonth, $endOfMonth) {

            return EnrolledCourse::with(['payments' => function ($q) use ($month, $year) {
                // Apply month filter only if $month is provided
                $q->when($month, function ($query) use ($month) {
                    $query->whereMonth('payment_date', $month);
                });

                // Apply year filter only if $year is provided
                $q->when($year, function ($query) use ($year) {
                    $query->whereYear('payment_date', $year);
                });

                $q->where('is_deleted', 0);
            }])
            ->where('is_deleted', 0)
            ->whereHas('student', function ($q) {
                $q->where('is_deleted', 0);
            })
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [$startOfMonth, $endOfMonth])
            ->get()
            ->sum(function ($course) {
                $totalPaid = $course->payments->sum('paid_amount');
                $totalFee  = $course->total_fee;

                return $totalPaid < $totalFee ? $totalFee - $totalPaid : 0;
            });
        });
    }

    /**
     * Clear the cache for a given month and year
     *
     * @param int $month
     * @param int $year
     * @return void
     */
    public static function clear($month, $year)
    {
        $cacheKey = "enrolled_course_due_{$year}_{$month}";
        Cache::forget($cacheKey);
    }
}
