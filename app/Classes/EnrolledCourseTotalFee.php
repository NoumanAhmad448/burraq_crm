<?php

namespace App\Classes;

use App\Models\EnrolledCourse;
use Illuminate\Support\Facades\Cache;

class EnrolledCourseTotalFee
{
    /**
     * Get total course fee for all active students and courses
     * TTL = 1 minute
     *
     * @param int $ttl
     * @return float
     */
    public static function get($ttl = 1)
    {
        $cacheKey = "enrolled_course_total_fee";

        return Cache::remember($cacheKey, $ttl, function () {

            $totalFee = EnrolledCourse::where("is_deleted", 0)
                ->whereHas("student", function($query){
                    $query->where("is_deleted",0);
                })
                ->sum('total_fee');

            return $totalFee;
        });
    }

    /**
     * Clear cache
     */
    public static function clear()
    {
        Cache::forget("enrolled_course_total_fee");
    }
}
