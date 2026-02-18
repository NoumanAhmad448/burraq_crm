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
                ->where('ec.is_deleted', 0)
                ->whereNull('ec.status')
                ->where('ec.due_date', '<', now())
                ->count();

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
