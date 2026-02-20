<?php

namespace App\Classes;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentMonthYearCache
{
    /**
     * Get student count filtered by registration month/year (cached)
     *
     * @param int|null $month
     * @param int|null $year
     * @param int $ttlSeconds  Cache TTL in seconds (default 60)
     */
    public static function get(Request $request, ?int $month = null, ?int $year = null, int $ttlSeconds = 1): int
    {
        if (empty($request->get("month")) && empty($request->get("year"))) {
            $month = null;
            $year = null;
        }
        // dump($month);
        // dd($year);
        // // return Cache::remember(
        //     self::cacheKey($month, $year),
        //     now()->addSeconds($ttlSeconds),
        //     function () use ($month, $year) {
        $query = Student::active()
                        ->regDate($month, $year);
        // \printQuery($query);
        return $query
            ->count();
        // }
        // );
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
