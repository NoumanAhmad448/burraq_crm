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
        $totalPaid_m =  DB::table('crm_enrolled_courses as ec')
            ->join('crm_students as s', function ($join) {
                $join->on('s.id', '=', 'ec.student_id')
                    ->where('s.is_deleted', 0);
            })
            ->leftJoin(DB::raw("
                    (
                        SELECT 
                            enrolled_course_id,
                            SUM(
                                CASE 
                                    WHEN type = 'refunded' THEN -paid_amount
                                    ELSE paid_amount
                                END
                            ) as total_paid
                        FROM crm_course_payments
                        WHERE is_deleted = 0
                        AND payment_date BETWEEN ? AND ?
                        GROUP BY enrolled_course_id
                    ) as p
                "), 'p.enrolled_course_id', '=', 'ec.id')
                ->addBinding([$startOfMonth, $endOfMonth], 'join')
            ->where('ec.is_deleted', 0)
            ->whereNull('ec.status')
            ->whereRaw('COALESCE(p.total_paid,0) >= ec.total_fee')
            ->sum('p.total_paid');

        return $totalPaid_m;


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
