<?php

namespace App\Classes;

use App\Models\EnrolledCourse;
use Illuminate\Support\Facades\Cache;

class EnrolledCoursePendingThisMonth
{
    /**
     * Get total pending amount for this month (only positive unpaid)
     * TTL = 1 minute
     *
     * @param string $startOfMonth
     * @param string $endOfMonth
     * @param int $ttl Time to live in minutes
     * @return float
     */
    public static function get($startOfMonth, $endOfMonth, $ttl = 1)
    {
        $cacheKey = "enrolled_course_pending_this_month_{$startOfMonth}_{$endOfMonth}";

        return Cache::remember($cacheKey, $ttl, function () use ($startOfMonth, $endOfMonth) {

            $pendingThisMonth = EnrolledCourse::with('payments', 'student')
                ->whereHas('student', fn($q) => $q->where('is_deleted', 0)) // only active students
                ->where("is_deleted", 0)
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

            return $pendingThisMonth;
        });
    }

    /**
     * Clear cached pending this month
     *
     * @param string $startOfMonth
     * @param string $endOfMonth
     * @return void
     */
    public static function clear($startOfMonth, $endOfMonth)
    {
        Cache::forget("enrolled_course_pending_this_month_{$startOfMonth}_{$endOfMonth}");
    }
}
