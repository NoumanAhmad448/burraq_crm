<?php

namespace App\Classes;

use App\Models\EnrolledCourse;
use Illuminate\Support\Facades\Cache;

class EnrolledCourseTotalOverdueCount
{
    /**
     * Get total count of overdue courses for this month
     * TTL = 1 minute
     *
     * @param int $ttl Time to live in minutes
     * @return int
     */
    public static function get($ttl = 1)
    {
        $cacheKey = "enrolled_course_total_overdue_count";

        return Cache::remember($cacheKey, $ttl, function () {

            $totalOverdue_count = EnrolledCourse
                ::pendingCourses()->with('student', 'payments')
                ->whereHas('student', function ($query) {
                        $query->where('is_deleted', 0);
                    })
                ->activeCourse()
                ->paidStudentsOnly()->count();

            return $totalOverdue_count;
        });
    }

    /**
     * Clear cached total overdue count
     *
     * @return void
     */
    public static function clear()
    {
        Cache::forget("enrolled_course_total_overdue_count");
    }
}
