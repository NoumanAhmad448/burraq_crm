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
            ->from('crm_course_payments as cp')
            ->where('cp.is_deleted', 0)
            ->whereBetween('cp.payment_date', [$startOfMonth, $endOfMonth])
            ->whereExists(function ($q) {
                $q->selectRaw(1)
                    ->from('crm_enrolled_courses as ec')
                    ->join('crm_students as s', 's.id', '=', 'ec.student_id')
                    ->whereColumn('ec.id', 'cp.enrolled_course_id')
                    ->where('ec.is_deleted', 0)
                    ->where('s.is_deleted', 0);
            })
            ->netAmount();
        // \printQuery($paymentsThisMonth);
        return $paymentsThisMonth->value('amount');
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
