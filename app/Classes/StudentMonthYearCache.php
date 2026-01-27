<?php

namespace App\Classes;

use App\Models\Student;
use Illuminate\Support\Facades\Cache;

class StudentMonthYearCache
{
    protected ?int $month;
    protected ?int $year;
    protected int $cacheMinutes;

    public function __construct(?int $month = null, ?int $year = null, int $cacheMinutes = 60)
    {
        $this->month = $month;
        $this->year = $year;
        $this->cacheMinutes = $cacheMinutes;
    }

    /**
     * Generate a unique cache key based on month/year
     */
    protected static function cacheKey($month, $year): string
    {
        return 'active_students_' . ($month ?? 'all') . '_' . ($year ?? 'all');
    }

    /**
     * Get active students with month/year filter and cache
     */
    public static function get(?int $month = null, ?int $year = null, int $cacheMinutes = 1)
    {
        return Cache::remember(self::cacheKey($month, $year), $cacheMinutes, function () use($month, $year) {
            return Student::where('is_deleted', 0)
                ->when(!is_null($month), function($q) use($month){
                    return  $q->whereMonth('registration_date', $month);
                    })
                ->when(!is_null($year), function($q) use($year){return  $q->whereYear('registration_date', $year);})
                ->count();
        });
    }
}
