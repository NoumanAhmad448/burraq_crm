<?php

namespace App\Classes;

use App\Models\EnrolledCourse;
use Illuminate\Support\Facades\Cache;

class EnrolledCourseTotalPaidMonth
{
    /**
     * Get total paid amount for this month (only fully paid courses)
     * TTL = 1 minute
     *
     * @param string $startOfMonth
     * @param string $endOfMonth
     * @param int $ttl
     * @return float
     */
    public static function get($startOfMonth, $endOfMonth, $ttl = 1)
    {
        $cacheKey = "enrolled_course_total_paid_month_{$startOfMonth}_{$endOfMonth}";

        return Cache::remember($cacheKey, $ttl, function () use ($startOfMonth, $endOfMonth) {

            $totalPaid_m = EnrolledCourse::query()
                ->where('is_deleted', 0) // course active
                ->whereHas('student', fn ($q) => $q->where('is_deleted', 0))
                ->withSum(['payments as total_paid' => function ($q) use($startOfMonth,$endOfMonth){
                    $q->where('is_deleted', 0)->whereBetween('payment_date', [$startOfMonth, $endOfMonth]);
                }], 'paid_amount')
                ->get()
                ->filter(fn ($course) => $course->total_paid >= $course->total_fee)
                ->sum('total_paid');

            return $totalPaid_m;
        });
    }

    /**
     * Clear cached total paid this month
     *
     * @param string $startOfMonth
     * @param string $endOfMonth
     * @return void
     */
    public static function clear($startOfMonth, $endOfMonth)
    {
        Cache::forget("enrolled_course_total_paid_month_{$startOfMonth}_{$endOfMonth}");
    }
}
