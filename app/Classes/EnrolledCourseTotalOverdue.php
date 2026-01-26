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

        return Cache::remember($cacheKey, $ttl, function () {

            $totalOverdue = EnrolledCourse::
                whereHas('student', fn($q) => $q->where('is_deleted', 0)) // only active students
                ->whereNotNull('due_date') // past due
                ->where('due_date', '<', now()) // past due
                ->where('is_deleted', 0)
                ->withSum(['payments as total_paid' => function ($q) {
                        $q->where('is_deleted', 0);
                    }], 'paid_amount')
                ->get()
                ->sum(function ($course) {
                    return $course->total_paid < $course->total_fee ? $course->total_fee - $course->total_paid  : 0; // only positive unpaid
                });

            return $totalOverdue;
        });
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
