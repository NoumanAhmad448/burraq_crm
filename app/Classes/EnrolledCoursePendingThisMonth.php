<?php

namespace App\Classes;

use App\Models\EnrolledCourse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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

        // return Cache::remember($cacheKey, $ttl, function () use ($startOfMonth, $endOfMonth) {

        $pendingThisMonth = DB::table('crm_enrolled_courses as ec')
            ->join('crm_students as s', function ($join) {
                $join->on('s.id', '=', 'ec.student_id')
                    ->where('s.is_deleted', 0);
            })
            ->leftJoin('enrolled_course_payments as p', function ($join) use ($startOfMonth, $endOfMonth) {
                $join->on('p.enrolled_course_id', '=', 'ec.id')
                    ->where('p.is_deleted', 0)
                    ->whereBetween('p.payment_date', [$startOfMonth, $endOfMonth]);
            })
            ->where('ec.is_deleted', 0)
            ->groupBy('ec.id', 'ec.total_fee')
            ->selectRaw("
                SUM(
                    CASE
                        WHEN (
                            COALESCE(
                                SUM(
                                    CASE
                                        WHEN p.type = 'refunded' THEN -p.paid_amount
                                        ELSE p.paid_amount
                                    END
                                ), 0
                            )
                        ) < ec.total_fee
                        THEN ec.total_fee -
                            COALESCE(
                                SUM(
                                    CASE
                                        WHEN p.type = 'refunded' THEN -p.paid_amount
                                        ELSE p.paid_amount
                                    END
                                ), 0
                            )
                        ELSE 0
                    END
                ) as total_outstanding
            ")
            ->value('total_outstanding');



        return $pendingThisMonth;
        // });
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
