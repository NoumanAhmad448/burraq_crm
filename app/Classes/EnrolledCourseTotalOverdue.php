<?php

namespace App\Classes;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class EnrolledCourseTotalOverdue
{
    /**
     * Get total overdue amount (only positive unpaid)
     * TTL = 1 minute
     *
     * @param int $ttl Time to live in minutes
     * @return float
     */
    public static function get($ttl = 1)
    {
        $cacheKey = "enrolled_course_total_overdue";

        // return Cache::remember($cacheKey, $ttl, function () {

            $totalOverdue = DB::table('crm_enrolled_courses as ec')
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

            return $totalOverdue;
        // });
    }

    /**
     * Clear cached total overdue
     *
     * @return void
     */
    public static function clear()
    {
        Cache::forget("enrolled_course_total_overdue");
    }
}
