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

        // return Cache::remember($cacheKey, $ttl, function () {

            $totalUnpaid_count = EnrolledCourse::query()
                ->paidStudentsOnly()
                ->activeStudentInRelation()
                ->whereHas('payments', function ($q){
                    $q->active();
                })
                // ->totalActivePayment()
                ->activeStatus()
                ;

                // \printQuery($totalUnpaid_count);
                
                

            return $totalUnpaid_count->count();
        // });
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
