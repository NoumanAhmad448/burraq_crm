<?php

namespace App\Classes;

use App\Models\Student as CRMStudent;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class StudentYearly
{
    /**
     * Get students grouped by month for a given year
     * Uses caching to avoid repeated DB queries
     *
     * @param int|null $year
     * @param int $ttl Time to live in minutes
     * @return \Illuminate\Support\Collection
     */
    public static function get($year = null, $ttl = 1)
    {
        $year = $year ?? Carbon::now()->year;

        $cacheKey = "students_yearly_{$year}";

        return Cache::remember($cacheKey, $ttl, function () use ($year) {
            return CRMStudent::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
                ->whereYear('created_at', $year)
                ->where('is_deleted', 0)
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        });
    }

    /**
     * Clear the cache for a given year
     *
     * @param int|null $year
     * @return void
     */
    public static function clear($year = null)
    {
        $year = $year ?? Carbon::now()->year;

        $cacheKey = "students_yearly_{$year}";
        Cache::forget($cacheKey);
    }
}
