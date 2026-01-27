<?php

namespace App\Classes;

use App\Models\EnrolledCourse;
use Illuminate\Support\Facades\Cache;

class EnrolledCoursePaidCache
{
    /**
     * Get fully paid enrolled courses filtered by payment month/year (cached)
     *
     * @param int|null $month
     * @param int|null $year
     * @param int $ttlSeconds
     */
    public static function get(?int $month = null, ?int $year = null, int $ttlSeconds = 1)
    {
        $cacheKey = self::cacheKey($month, $year);

        return Cache::remember($cacheKey, $ttlSeconds, function () use ($month, $year) {

            return EnrolledCourse::with([
                    'student',
                    'payments' => function ($q) use ($month, $year) {
                        $q->where('is_deleted', 0)
                          ->when(!is_null($month), fn ($qq) =>
                              $qq->whereMonth('payment_date', $month)
                          )
                          ->when(!is_null($year), fn ($qq) =>
                              $qq->whereYear('payment_date', $year)
                          );
                    }
                ])
                ->where('is_deleted', 0)
                ->whereHas('student', fn ($q) => $q->where('is_deleted', 0))
                ->get()
                ->filter(function ($enrolledCourse) {
                    $totalPaid = $enrolledCourse->payments->sum('paid_amount');
                    return $totalPaid >= $enrolledCourse->total_fee;
                })
                ->sortByDesc('created_at')
                ->values();
        });
    }

    /**
     * Cache key generator
     */
    protected static function cacheKey(?int $month, ?int $year): string
    {
        return 'enrolled_courses_paid_'
            . ($month ?? 'all') . '_'
            . ($year ?? 'all');
    }
}
