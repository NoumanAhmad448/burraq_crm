<?php

namespace App\Classes;

use Illuminate\Support\Facades\DB;
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
        // dump($startOfMonth);
        // dd($endOfMonth);
        $cacheKey = "enrolled_course_due_this_month_{$startOfMonth}_{$endOfMonth}";

        // return Cache::remember($cacheKey, $ttl, function () use ($startOfMonth, $endOfMonth) {

        $dueThisMonth = DB::table('crm_enrolled_courses as ec')
                        ->join('crm_students as s', function ($join) {
                            $join->on('s.id', '=', 'ec.student_id')
                                ->where('s.is_deleted', 0);
                        })
                        ->join(DB::raw("
                            (
                                SELECT 
                                    enrolled_course_id,
                                    COALESCE(
                                        SUM(
                                            CASE 
                                                WHEN type = 'refunded' THEN -paid_amount
                                                ELSE paid_amount
                                            END
                                        ), 0
                                    ) as total_paid
                                FROM crm_course_payments
                                WHERE is_deleted = 0
                                AND payment_date BETWEEN '{$startOfMonth}' AND '{$endOfMonth}'
                                GROUP BY enrolled_course_id
                            ) as p
                        "), 'p.enrolled_course_id', '=', 'ec.id')
                                ->where('ec.is_deleted', 0)
                                ->whereNull('ec.status')
                                ->whereNotNull('ec.due_date')
                                ->where('ec.due_date', '<', now())
                                ->selectRaw("
                            SUM(
                                GREATEST(
                                    ec.total_fee - COALESCE(p.total_paid, 0),
                                0)
                            ) as total_outstanding
                        ")
            ->value('total_outstanding');



        return $dueThisMonth;
        // });
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
