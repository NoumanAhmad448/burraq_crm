<?php

namespace App\Classes;

use App\Models\EnrolledCourse;
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

            $totalOverdue = EnrolledCourse::query()
                ->whereHas('student', fn ($q) => $q->where('is_deleted', 0))
                ->whereNotNull('due_date')
                ->where('due_date', '<', now())
                ->where('is_deleted', 0)
                ->netAmount();

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
