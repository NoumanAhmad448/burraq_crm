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
    public static function get(?int $month = null, ?int $year = null, int $ttlSeconds = 1, $status = "")
    {
        $cacheKey = self::cacheKey($month, $year);

        return Cache::remember($cacheKey, $ttlSeconds, function () use ($month, $year, $status) {

            return EnrolledCourse::with(['payments'])
                ->activeCourse()
                ->whereHas('student', function ($q) use ($month, $year, $status) {
                    $q->regDate($month, $year)->active()->ignoreOrAccept($status);
                })
                ->getCourse()
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
