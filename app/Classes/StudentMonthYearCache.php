<?php

namespace App\Classes;

use App\Models\Student;
use Illuminate\Support\Facades\Cache;

class StudentMonthYearCache
{
    /**
     * Get student count filtered by registration month/year (cached)
     *
     * @param int|null $month
     * @param int|null $year
     * @param int $ttlSeconds  Cache TTL in seconds (default 60)
     */
    public static function get(?int $month = null, ?int $year = null, int $ttlSeconds = 1): int
    {
        return Cache::remember(
            self::cacheKey($month, $year),
            now()->addSeconds($ttlSeconds),
            function () use ($month, $year) {

                return Student::where('is_deleted', 0)
                    ->when(!is_null($month), fn ($q) =>
                        $q->whereMonth('registration_date', $month)
                    )
                    ->when(!is_null($year), fn ($q) =>
                        $q->whereYear('registration_date', $year)
                    )
                    ->count();
            }
        );
    }

    /**
     * Cache key generator
     */
    protected static function cacheKey(?int $month, ?int $year): string
    {
        return 'students_count_'
            . ($month ?? 'all') . '_'
            . ($year ?? 'all');
    }
}
