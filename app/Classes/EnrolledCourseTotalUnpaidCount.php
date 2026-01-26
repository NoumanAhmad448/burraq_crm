<?php

namespace App\Classes;

use App\Models\EnrolledCourse;
use Illuminate\Support\Facades\Cache;

class EnrolledCourseTotalUnpaidCount
{
    /**
     * Get total count of unpaid courses (where total_paid < total_fee)
     * TTL = 1 minute
     *
     * @param int $ttl Time to live in minutes
     * @return int
     */
    public static function get($ttl = 1)
    {
        $cacheKey = "enrolled_course_total_unpaid_count";

        return Cache::remember($cacheKey, $ttl, function () {

            $totalUnpaid_count = EnrolledCourse::with('payments')
                ->whereHas('student', fn($q) => $q->where("is_deleted", 0))
                ->where("is_deleted", 0)
                ->totalActivePayment()
                ->get()
                ->filter(function ($course) {
                    return $course->total_paid < $course->total_fee; // only positive unpaid
                })->count();

            return $totalUnpaid_count;
        });
    }

    /**
     * Clear cached total unpaid count
     *
     * @return void
     */
    public static function clear()
    {
        Cache::forget("enrolled_course_total_unpaid_count");
    }
}
