<?php

namespace App\Classes;

use App\Models\EnrolledCourse;
use Illuminate\Database\Eloquent\Builder;

class EnrolledCourseStudentFilter
{
    /**
     * Build the filtered query
     */
    public static function query(?int $month = null, ?int $year = null, $status="")
    {
        return EnrolledCourse::with(['student', 'payments'])
            ->whereHas('student', function ($query) use ($month, $year, $status) {
                $query->when($status, function($q, $status){
                    $q->where("status", empty($status) ? "<>" : "=", empty($status) ? "Completed" : $status);
                })
                ->where('is_deleted', 1)
                      ->when(!is_null($month), function ($q) use ($month) {
                          $q->whereMonth('registration_date', $month);
                      })
                      ->when(!is_null($year), function ($q) use ($year) {
                          $q->whereYear('registration_date', $year);
                      });
            })
            ->latest('created_at')->get();
    }

    /**
     * Shortcut to get results
     */
    public static function get(?int $month = null, ?int $year = null)
    {
        return self::query($month, $year)->get();
    }
}
