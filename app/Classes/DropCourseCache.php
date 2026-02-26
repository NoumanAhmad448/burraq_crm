<?php

namespace App\Classes;

use Illuminate\Support\Facades\Cache;

class DropCourseCache
{
    /**
     * Get enrolled courses filtered by student registration month/year (cached)
     *
     * @param int|null $month
     * @param int|null $year
     * @param int $ttlSeconds
     */
    public static function get(?int $month = null, ?int $year = null, int $ttlSeconds = 1, $status = "")
    {
        $cacheKey = self::cacheKey($month, $year);

        // return Cache::remember($cacheKey, $ttlSeconds, function () use ($month, $year, $status) {

            $query =  EnrolledCourseDuePaymentCache::commonLogic($month, $year, $status, "payment_date")
                ->droppedCourse();

                // printQuery($query);
                return $query->latest()
                ->get();
        // });
    }

    /**
     * Cache key generator
     */
    protected static function cacheKey(?int $month, ?int $year): string
    {
        return 'student_dropped_courses_'
            . ($month ?? 'all') . '_'
            . ($year ?? 'all');
    }
}
