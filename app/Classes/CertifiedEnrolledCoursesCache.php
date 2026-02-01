<?php

namespace App\Classes;

use App\Models\EnrolledCourse;
use Illuminate\Support\Facades\Cache;

class CertifiedEnrolledCoursesCache
{
    private const CACHE_KEY = 'certified_enrolled_courses';

    public static function get($cache_ttle = 1)
    {
        return Cache::remember(self::CACHE_KEY, $cache_ttle, function () {
            return EnrolledCourse::with([
                    'student',
                    'payments',
                    'certificate' => fn ($q) => $q->where('generated_count', '>', 0),
                ])
                ->whereHas('certificate', fn ($q) =>
                    $q->whereNotNull('generated_count')
                      ->where('generated_count', '>', 0)
                )
                ->whereHas('student', fn ($q) => $q->where('is_deleted', 0))
                ->where('is_deleted', 0)
                ->latest()
                ->get();
        });
    }

    public static function forget(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
