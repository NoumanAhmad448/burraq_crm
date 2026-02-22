<?php

namespace App\Classes;

use Illuminate\Support\Facades\DB;
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
