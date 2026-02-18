<?php

namespace App\Classes;

use App\Models\EnrolledCoursePayment;
use Illuminate\Support\Facades\Cache;

class EnrolledCoursePaymentsThisMonth
{
    /**
     * Get total paid amount for this month
     * TTL = 1 minute
     *
     * @param string $startOfMonth
     * @param string $endOfMonth
     * @param int $ttl
     * @return float
     */
    public static function get($startOfMonth, $endOfMonth, $ttl = 1)
    {
        $cacheKey = "enrolled_course_payments_this_month_{$startOfMonth}_{$endOfMonth}";

        // return Cache::remember($cacheKey, $ttl, function () use ($startOfMonth, $endOfMonth) {

            $paymentsThisMonth = EnrolledCoursePayment::query()
                    ->join('enrolled_courses', 'enrolled_courses.id', '=', 'enrolled_course_payments.enrolled_course_id')
                    ->join('students', 'students.id', '=', 'enrolled_courses.student_id')
                    ->whereBetween('enrolled_course_payments.payment_date', [$startOfMonth, $endOfMonth])
                    ->where('enrolled_course_payments.is_deleted', 0)
                    ->where('enrolled_courses.is_deleted', 0)
                    ->where('students.is_deleted', 0)
                    ->selectRaw("
                        COALESCE(
                            SUM(
                                CASE 
                                    WHEN enrolled_course_payments.type = 'refunded' 
                                    THEN -enrolled_course_payments.paid_amount
                                    ELSE enrolled_course_payments.paid_amount
                                END
                            ), 0
                        ) as total
                    ")
                    ->value('total');

            return $paymentsThisMonth;
        // });
    }

    /**
     * Clear cache
     *
     * @param string $startOfMonth
     * @param string $endOfMonth
     * @return void
     */
    public static function clear($startOfMonth, $endOfMonth)
    {
        Cache::forget("enrolled_course_payments_this_month_{$startOfMonth}_{$endOfMonth}");
    }
}
