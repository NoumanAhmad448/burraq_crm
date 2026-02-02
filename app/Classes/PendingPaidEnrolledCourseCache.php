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
    public static function get(?int $month = null, ?int $year = null, int $ttlSeconds = 1, $status="")
    {
        $cacheKey = self::cacheKey($month, $year);

        return Cache::remember($cacheKey, $ttlSeconds, function () use ($month, $year, $status) {

            return EnrolledCourse::pendingCourses()
                ->activeCourse()
                ->paidStudentsOnly()
                ->with([
                    'student'
                ])
                ->whereHas('payments' , function ($q) use ($month, $year) {
                        $q->where('is_deleted', 0)
                          ->when(!is_null($month), fn ($qq) =>
                              $qq->whereMonth('payment_date', $month)
                          )
                          ->when(!is_null($year), fn ($qq) =>
                              $qq->whereYear('payment_date', $year)
                          );
                })
                ->whereHas('student', fn ($q) => $q->where('is_deleted', 0)->where("status",  empty($status) ? "<>" : "=", empty($status) ? "Completed" : $status))
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
