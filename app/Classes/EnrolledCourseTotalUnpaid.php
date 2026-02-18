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

        // return Cache::remember($cacheKey, $ttl, function () {

            $totalUnpaid = EnrolledCourse::query()
                ->whereHas('student', fn ($q) => $q->where('is_deleted', 0))
                ->where('is_deleted', 0)
                ->select('*')
                ->selectSub(function ($q) {
                    $q->from('enrolled_course_payments')
                    ->whereColumn('enrolled_course_payments.enrolled_course_id', 'enrolled_courses.id')
                    ->where('is_deleted', 0)
                    ->selectRaw("
                        COALESCE(
                            SUM(
                                CASE 
                                    WHEN type = 'refunded' THEN -paid_amount
                                    ELSE paid_amount
                                END
                            ), 0
                        )
                    ");
                }, 'total_paid')
                ->get()
                ->sum(function ($course) {
                    return $course->total_paid < $course->total_fee
                        ? $course->total_fee - $course->total_paid
                        : 0;
                });

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
