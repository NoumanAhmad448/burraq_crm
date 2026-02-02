<?php

namespace App\Classes;

use App\Models\EnrolledCourse;
use Illuminate\Support\Facades\Cache;

class StudentEnrolledCourseCache
{
    /**
     * Get enrolled courses filtered by student registration month/year (cached)
     *
     * @param int|null $month
     * @param int|null $year
     * @param int $ttlSeconds
     */
    public static function get(?int $month = null, ?int $year = null, int $ttlSeconds = 1, $status="")
    {
        $cacheKey = self::cacheKey($month, $year);

        return Cache::remember($cacheKey, $ttlSeconds, function () use ($month, $year, $status) {

            return EnrolledCourse::with(['student', 'payments'])
                ->where('is_deleted', 0)
                ->whereHas('student', function ($q) use ($month, $year, $status) {
                    $q->where('is_deleted', 0)
                    ->where("status",  empty($status) ? "<>" : "=", empty($status) ? "Completed" : $status)
                      ->when(!is_null($month), fn ($qq) =>
                          $qq->whereMonth('registration_date', $month)
                      )
                      ->when(!is_null($year), fn ($qq) =>
                          $qq->whereYear('registration_date', $year)
                      );
                })
                ->latest()
                ->get();
        });
    }

    /**
     * Cache key generator
     */
    protected static function cacheKey(?int $month, ?int $year): string
    {
        return 'student_enrolled_courses_'
            . ($month ?? 'all') . '_'
            . ($year ?? 'all');
    }
}
