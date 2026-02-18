<?php

namespace App\Classes;

use App\Models\EnrolledCourse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class EnrolledCourseTotalUnpaid
{
    /**
     * Get total unpaid amount (only positive unpaid)
     * TTL = 1 minute
     *
     * @param int $ttl Time to live in minutes
     * @return float
     */
    public static function get($ttl = 1)
    {
        $cacheKey = "enrolled_course_total_unpaid";

        // return Cache::remember($cacheKey, $ttl, function () {


        $paymentsSub = DB::table('crm_course_payments as p')
            ->selectRaw("
                    p.enrolled_course_id,
                    SUM(
                        CASE
                            WHEN p.type = 'refunded' THEN -p.paid_amount
                            ELSE p.paid_amount
                        END
                    ) as total_paid
                ")
            ->where('p.is_deleted', 0)
            ->groupBy('p.enrolled_course_id');

        $totalUnpaid = DB::table('crm_enrolled_courses as ec')
            ->join('crm_students as s', function ($join) {
                $join->on('s.id', '=', 'ec.student_id')
                    ->where('s.is_deleted', 0);
            })
            ->leftJoinSub($paymentsSub, 'payments', function ($join) {
                $join->on('payments.enrolled_course_id', '=', 'ec.id');
            })
            ->where('ec.is_deleted', 0)
            ->selectRaw("
                    SUM(
                        CASE
                            WHEN COALESCE(payments.total_paid, 0) < ec.total_fee
                            THEN ec.total_fee - COALESCE(payments.total_paid, 0)
                            ELSE 0
                        END
                    ) as total_outstanding
                ")
            ->value('total_outstanding');


        return $totalUnpaid;
        // });
    }

    /**
     * Clear cached total unpaid
     *
     * @return void
     */
    public static function clear()
    {
        Cache::forget("enrolled_course_total_unpaid");
    }
}
