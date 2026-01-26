<?php

namespace App\Classes;

use App\Models\EnrolledCourse;
use Illuminate\Support\Facades\Cache;

class EnrolledCourseDueThisMonth
{
    /**
     * Get total due for this month (only unpaid amounts)
     * TTL = 1 minute
     *
     * @param string $startOfMonth
     * @param string $endOfMonth
     * @param int $ttl Time to live in minutes
     * @return float
     */
    public static function get($startOfMonth, $endOfMonth, $ttl = 1)
    {
        $cacheKey = "enrolled_course_due_this_month_{$startOfMonth}_{$endOfMonth}";

        return Cache::remember($cacheKey, $ttl, function () use ($startOfMonth, $endOfMonth) {

            $dueThisMonth = EnrolledCourse::
                whereHas('student', fn($q) => $q->where('is_deleted', 0)) // only active students
                ->whereNotNull('due_date') // past due
                ->where('due_date', '<', now()) // past due
                ->where('is_deleted', 0)
                ->whereHas("payments", function($q)  use($startOfMonth, $endOfMonth){
                    $q->whereBetween('payment_date', [$startOfMonth, $endOfMonth]);
                })
                ->withSum(['payments as total_paid' => function ($q){
                        $q->where('is_deleted', 0);
                    }], 'paid_amount')
                ->get()
                ->sum(function ($course) {
                    return $course->total_paid < $course->total_fee ? $course->total_fee - $course->total_paid  : 0; // only positive unpaid
                });

            return $dueThisMonth;
        });
    }

    /**
     * Clear cached due this month
     *
     * @param string $startOfMonth
     * @param string $endOfMonth
     * @return void
     */
    public static function clear($startOfMonth, $endOfMonth)
    {
        Cache::forget("enrolled_course_due_this_month_{$startOfMonth}_{$endOfMonth}");
    }
}
