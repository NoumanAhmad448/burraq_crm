<?php

namespace App\Classes;

use App\Models\EnrolledCourse;
use Illuminate\Support\Facades\Cache;

class EnrolledCourseDuePaymentCache
{
    /**
     * Get enrolled courses with due payment (cached for 1 minute)
     */
    public static function get(?int $month = null, ?int $year = null, $ttl=1, $status="")
    {
        $cacheKey = self::cacheKey($month, $year);

        return Cache::remember($cacheKey, $ttl, function () use($month, $year, $status) {

            return EnrolledCourse::with([
                    'student'
                ])
                ->whereHas('payments' , function ($q) use ($month, $year) {
                        $q = $q->where('is_deleted', 0);
                        (new EnrolledCourse)->regDate($q, $month, $year, "payment_date");
                })
                ->where('is_deleted', 0)
                ->whereHas('student', function($q) use($status){
                    $q = $q->where('is_deleted', 0); 
                    (new EnrolledCourse)->ignoreOrAccept($q, $status);
                })
                ->getCourse()
                ->get()
                ->filter(function ($enrolledCourse) {
                    $totalPaid = $enrolledCourse->payments->sum('paid_amount');
                    return $totalPaid < $enrolledCourse->total_fee;
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
        return 'due_enrolled_courses_payment_'
            . ($month ?? 'all') . '_'
            . ($year ?? 'all');
    }
}
