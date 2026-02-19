<?php

namespace App\Classes;

use App\Models\EnrolledCourse;
use Illuminate\Support\Facades\Cache;

class PendingPaidEnrolledCourseCache
{
    /**
     * Get pending + active + paid enrolled courses
     * filtered by payment month/year (cached)
     *
     * @param int|null $month
     * @param int|null $year
     * @param int $ttlSeconds
     */
    public static function get(?int $month = null, ?int $year = null, int $ttlSeconds = 1, $status = "")
    {
        $cacheKey = self::cacheKey($month, $year);

        return Cache::remember($cacheKey, $ttlSeconds, function () use ($month, $year, $status) {

            return
                EnrolledCourseDuePaymentCache::commonLogic($month, $year, $status, "payment_date")
                ->pendingCourses()
                ->paidStudentsOnly()
                ->activeStatus()
                ->latest()
                ->get();
        });
    }

    /**
     * Cache key generator
     */
    protected static function cacheKey(?int $month, ?int $year): string
    {
        return 'pending_paid_enrolled_courses_'
            . ($month ?? 'all') . '_'
            . ($year ?? 'all');
    }
}
