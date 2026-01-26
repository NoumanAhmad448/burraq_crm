<?php

namespace App\Classes;

use App\Models\EnrolledCourse;
use Illuminate\Support\Facades\Cache;

class EnrolledCourseWithCertificate
{
    /**
     * Get enrolled courses with certificates where total_paid >= total_fee
     * TTL = 1 minute
     *
     * @param int $ttl Time to live in minutes
     * @return \Illuminate\Support\Collection
     */
    public static function get($ttl = 1)
    {
        $cacheKey = "enrolled_courses_with_certificate";

        return Cache::remember($cacheKey, $ttl, function () {

            $enrolledCourses = EnrolledCourse::withSum(['payments as total_paid' => function ($q) {
                    $q->where('is_deleted', 0);
                }], 'paid_amount')
                ->whereHas('certificate')
                ->whereHas('student', fn ($q) => $q->where('is_deleted', 0))
                ->get()
                ->filter(fn ($ec) => ($ec->total_paid ?? 0) >= $ec->total_fee);

            return $enrolledCourses;
        });
    }

    /**
     * Clear cached enrolled courses with certificates
     *
     * @return void
     */
    public static function clear()
    {
        Cache::forget("enrolled_courses_with_certificate");
    }
}
