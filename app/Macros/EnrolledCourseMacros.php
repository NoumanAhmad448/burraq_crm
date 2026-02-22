<?php

namespace App\Macros;

use Illuminate\Database\Eloquent\Builder;
use App\Support\LyskillsCarbon;

class EnrolledCourseMacros
{
    /**
     * Register all macros for EnrolledCourse
     */
    public static function register(): void
    {
        /*
        |--------------------------------------------------------------------------
        | pendingCourses
        |--------------------------------------------------------------------------
        */
        Builder::macro('pendingCourses', function () {
            return $this->whereNotNull('due_date')
                        ->where('due_date', '<', now());
        });

        /*
        |--------------------------------------------------------------------------
        | totalActivePayment
        |--------------------------------------------------------------------------
        */
        Builder::macro('totalActivePayment', function () {
            return $this->withSum(['payments as total_paid' => function ($q) {
                $q->active()->noRefundedPayments();
            }], 'paid_amount');
        });

        /*
        |--------------------------------------------------------------------------
        | totalIncome
        |--------------------------------------------------------------------------
        */
        Builder::macro('totalIncome', function () {
            return $this->whereHas('student', function ($q) {
                $q->where('is_deleted', 0);
            })->where('is_deleted', 0)->sum("total_fee");
        });

        /*
        |--------------------------------------------------------------------------
        | totalMonthlyIncome
        |--------------------------------------------------------------------------
        */
        Builder::macro('totalMonthlyIncome', function ($month = null, $year = null) {
            return $this->whereHas('student', function ($q) {
                        $q->where('is_deleted', 0);
                    })
                    ->where('is_deleted', 0)
                    ->when($month, fn($query) => $query->whereMonth('admission_date', $month))
                    ->when($year, fn($query) => $query->whereYear('admission_date', $year))
                    ->sum("total_fee");
        });

        /*
        |--------------------------------------------------------------------------
        | activeCourse
        |--------------------------------------------------------------------------
        */
        Builder::macro('activeCourse', function () {
            return $this->where('is_deleted', '<>', 1);
        });

        /*
        |--------------------------------------------------------------------------
        | paidStudentsOnly
        |--------------------------------------------------------------------------
        */
        Builder::macro('paidStudentsOnly', function () {
            return $this->whereRaw(
                '(SELECT COALESCE(SUM(paid_amount), 0)
                  FROM crm_course_payments as payments
                  WHERE payments.enrolled_course_id = crm_enrolled_courses.id
                  AND payments.is_deleted = 0
                ) < crm_enrolled_courses.total_fee'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | formattedAdmissionDate (accessor as macro)
        |--------------------------------------------------------------------------
        */
        Builder::macro('formattedAdmissionDate', function ($admission_date) {
            if (!$admission_date) return null;
            return LyskillsCarbon::parse($admission_date)->format('d-m-Y');
        });

        /*
        |--------------------------------------------------------------------------
        | formattedDueDate (accessor as macro)
        |--------------------------------------------------------------------------
        */
        Builder::macro('formattedDueDate', function ($due_date) {
            if (!$due_date) return null;
            return LyskillsCarbon::parse($due_date)->format('d-m-Y');
        });
    }
}