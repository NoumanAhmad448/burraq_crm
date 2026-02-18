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

            $pendingThisMonth = EnrolledCourse::query()
                ->where('is_deleted', 0)
                ->whereHas('student', fn($q) => $q->where('is_deleted', 0))
                ->whereHas('payments', function ($q) use ($startOfMonth, $endOfMonth) {
                    $q->whereBetween('payment_date', [$startOfMonth, $endOfMonth])
                        ->where('is_deleted', 0);
                })
                ->select('*')
                ->selectSub(function ($q) use ($startOfMonth, $endOfMonth) {
                    $q->from('enrolled_course_payments')
                        ->whereColumn('enrolled_course_payments.enrolled_course_id', 'crm_enrolled_courses.id')
                        ->where('is_deleted', 0)
                        ->whereBetween('payment_date', [$startOfMonth, $endOfMonth])
                        ->selectRaw("
              SUM(
                  CASE 
                      WHEN type = 'refunded' THEN -paid_amount
                      ELSE paid_amount
                  END
              )
          ");
                }, 'total_paid')
                ->get()
                ->sum(function ($course) {
                    return $course->total_paid < $course->total_fee
                        ? $course->total_fee - $course->total_paid
                        : 0;
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
