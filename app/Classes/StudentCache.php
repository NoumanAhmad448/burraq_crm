<?php

namespace App\Classes;

use App\Models\Student as CRMStudent;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class StudentCache
{
    /**
     * Get students grouped by payment date for a given month and year
     * Uses caching to avoid repeated DB queries
     *
     * @param int|null $month
     * @param int|null $year
     * @param int $ttl Time to live in minutes
     * @return \Illuminate\Support\Collection
     */
    public static function studentsThisMonth($month = null, $year = null, $ttl = 1)
    {
        $month = $month ?? Carbon::now()->month;
        $year = $year ?? Carbon::now()->year;

        // Cache key
        $cacheKey = "students_this_month_{$year}_{$month}";

        // Return cached value or execute closure
        return Cache::remember($cacheKey, $ttl * 60, function () use ($month, $year) {
            return CRMStudent::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->where('is_deleted', 0)
                ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->get();
        });
    }

    /**
     * Clear the cache for a given month/year
     *
     * @param int|null $month
     * @param int|null $year
     * @return void
     */
    public static function clearCache($month = null, $year = null)
    {
        $month = $month ?? Carbon::now()->month;
        $year = $year ?? Carbon::now()->year;

        $cacheKey = "students_this_month_{$year}_{$month}";
        Cache::forget($cacheKey);
    }
}
