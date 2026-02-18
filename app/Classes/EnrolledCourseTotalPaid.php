<?php

namespace App\Classes;

use App\Models\EnrolledCourse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
                ->whereHas('student', fn($q) => $q->where('is_deleted', 0))
                ->withSum(['payments as total_paid' => fn($q) => $q->where('is_deleted', 0)], 'paid_amount')
                ->get()
                ->filter(fn($course) => $course->total_paid >= $course->total_fee)
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

        // return Cache::remember($cacheKey, $ttl, function () use ($startOfMonth, $endOfMonth) {
        return DB::table('crm_enrolled_courses as ec')
            ->join('crm_students as s', function ($join) {
                $join->on('s.id', '=', 'ec.student_id')
                    ->where('s.is_deleted', 0);
            })
            ->leftJoin('crm_course_payments as cp', function ($join) use ($startOfMonth, $endOfMonth) {
                $join->on('cp.enrolled_course_id', '=', 'ec.id')
                    ->where('cp.is_deleted', 0)
                    ->whereBetween('cp.payment_date', [$startOfMonth, $endOfMonth]);
            })
            ->where('ec.is_deleted', 0)
            ->groupBy('ec.id', 'ec.total_fee')
            ->havingRaw("
                COALESCE(
                    SUM(
                        cp.paid_amount *
                        (CASE WHEN cp.type = 'refunded' THEN -1 ELSE 1 END)
                    ), 0
                ) >= ec.total_fee
            ")
            ->selectRaw("
                SUM(
                    COALESCE(
                        cp.paid_amount *
                        (CASE WHEN cp.type = 'refunded' THEN -1 ELSE 1 END),
                        0
                    )
                ) as total
            ")
            ->value('total');


        // });
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
