<?php

namespace App\Classes;

use App\Models\EnrolledCourse;
use Illuminate\Support\Facades\Cache;

class EnrolledCourseTotalPaid
{
    /**
     * Get total paid for all courses (where total_paid >= total_fee)
     *
     * @param int $ttl Time to live in minutes
     * @return float
     */
    public static function getTotalPaid($ttl = 1)
    {
        $cacheKey = "enrolled_course_total_paid";

        return Cache::remember($cacheKey, $ttl, function () {
            return EnrolledCourse::query()
                ->where('is_deleted', 0)
                ->whereHas('student', fn ($q) => $q->where('is_deleted', 0))
                ->withSum(['payments as total_paid' => fn ($q) => $q->where('is_deleted', 0)], 'paid_amount')
                ->get()
                ->filter(fn ($course) => $course->total_paid >= $course->total_fee)
                ->sum('total_paid');
        });
    }

    /**
     * Get total paid for courses in a given month
     *
     * @param string $startOfMonth
     * @param string $endOfMonth
     * @param int $ttl Time to live in minutes
     * @return float
     */
    public static function getTotalPaidMonth($startOfMonth, $endOfMonth, $ttl = 1)
    {
        $cacheKey = "enrolled_course_total_paid_{$startOfMonth}_{$endOfMonth}";

        return Cache::remember($cacheKey, $ttl, function () use ($startOfMonth, $endOfMonth) {
            return EnrolledCourse::query()
                ->where('is_deleted', 0)
                ->whereHas('student', fn ($q) => $q->where('is_deleted', 0))
                ->withSum(['payments as total_paid' => function ($q) use ($startOfMonth, $endOfMonth) {
                    $q->where('is_deleted', 0)
                      ->whereBetween('payment_date', [$startOfMonth, $endOfMonth]);
                }], 'paid_amount')
                ->get()
                ->filter(fn ($course) => $course->total_paid >= $course->total_fee)
                ->sum('total_paid');
        });
    }

    /**
     * Clear all cached total paid values
     *
     * @param string|null $startOfMonth
     * @param string|null $endOfMonth
     * @return void
     */
    public static function clear($startOfMonth = null, $endOfMonth = null)
    {
        Cache::forget("enrolled_course_total_paid");

        if ($startOfMonth && $endOfMonth) {
            Cache::forget("enrolled_course_total_paid_{$startOfMonth}_{$endOfMonth}");
        }
    }
}
