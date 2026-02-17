<?php

namespace App\Classes;

use App\Models\EnrolledCourse;
use Illuminate\Database\Eloquent\Builder;

class EnrolledCourseStudentFilter
{
    /**
     * Build the filtered query
     */
    public static function query(?int $month = null, ?int $year = null, $status = "")
    {
        return EnrolledCourse::with(['student', 'payments'])
            ->whereHas('student', function ($query) use ($month, $year, $status) {
                $this->ignoreOrAccept($query, $status)
                    ->where('is_deleted', 1)
                    ->$this->regDate($month, $year);
            })
            ->latest('created_at');
    }

    /**
     * Shortcut to get results
     */
    public static function get(?int $month = null, ?int $year = null)
    {
        return self::query($month, $year)->get();
    }
    
}
