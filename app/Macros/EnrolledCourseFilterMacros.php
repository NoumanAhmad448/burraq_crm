<?php

namespace App\Macros;

use Illuminate\Database\Eloquent\Builder;

class EnrolledCourseFilterMacros
{
    /**
     * Register all filter-related macros for EnrolledCourse
     */
    public static function register(): void
    {
        /*
        |--------------------------------------------------------------------------
        | regDate
        |--------------------------------------------------------------------------
        */
        Builder::macro('regDate', function ($month = null, $year = null, $date = "admission_date") {
            return $this->when(!is_null($month), function ($q) use ($month, $date) {
                        $q->whereMonth($date, $month);
                    })
                    ->when(!is_null($year), function ($q) use ($year, $date) {
                        $q->whereYear($date, $year);
                    });
        });

        /*
        |--------------------------------------------------------------------------
        | dateFilter
        |--------------------------------------------------------------------------
        */
        Builder::macro('dateFilter', function ($startDate, $endDate, $date = "admission_date", $table_name="") {
            return $this->whereBetween($table_name ? $table_name.$date : $date, [$startDate, $endDate]);
        });

        /*
        |--------------------------------------------------------------------------
        | ignoreOrAccept
        |--------------------------------------------------------------------------
        */
        Builder::macro('ignoreOrAccept', function ($status) {
            return $this->when($status, function ($query, $status) {
                if (empty($status)) {
                    return $query
                    // ->where('status', '<>', self::COMPLETED)
                    ;
                }
            // dd($status);
             return $query->where('status', $status);
            });
        });

        /*
        |--------------------------------------------------------------------------
        | getCourse
        |--------------------------------------------------------------------------
        */
        Builder::macro('getCourse', function () {
            if (request()->course_id) {
                return $this->where('course_id', request()->course_id);
            }
            return $this;
        });

        /*
        |--------------------------------------------------------------------------
        | getGroup
        |--------------------------------------------------------------------------
        */
        Builder::macro('getGroup', function () {
            if (request()->group_id) {
                return $this->whereHas("groupEnrollment", function ($q) {
                    $q->where("group_id", request()->group_id);
                });
            }
            return $this;
        });
    }
}