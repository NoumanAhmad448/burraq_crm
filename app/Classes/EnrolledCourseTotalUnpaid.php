<?php

namespace App\Classes;

use App\Models\EnrolledCourse;
use Illuminate\Support\Facades\Cache;

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

        return Cache::remember($cacheKey, $ttl, function () {

            $totalUnpaid = EnrolledCourse::with('payments', 'student')
                ->whereHas('student', fn($q) => $q->where('is_deleted', 0)) // only active students
                ->where("is_deleted", 0)
                ->withSum(['payments as total_paid' => function ($q) {
                        $q->where('is_deleted', 0);
                    }], 'paid_amount')
                ->get()
                ->sum(function ($course) {
                    return $course->total_paid < $course->total_fee ? $course->total_fee - $course->total_paid  : 0; // only positive unpaid
                });

            return $totalUnpaid;
        });
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
