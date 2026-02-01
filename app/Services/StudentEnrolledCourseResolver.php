<?php

namespace App\Services;

use App\Classes\CertifiedEnrolledCoursesCache;
use App\Models\Course;
use App\Models\EnrolledCourse;
use App\Classes\EnrolledCourseStudentFilter;
use App\Classes\PendingPaidEnrolledCourseCache;
use App\Classes\StudentEnrolledCourseCache;
use App\Classes\EnrolledCourseDuePaymentCache;
use App\Classes\EnrolledCoursePaidCache;

class StudentEnrolledCourseResolver
{
    public static function resolve(string|null $type, ?int $month, ?int $year, $status)
    {
        return match ($type) {
            'deleted' => EnrolledCourseStudentFilter::query($month, $year, $status),

            'unpaid' => EnrolledCourseDuePaymentCache::get($month, $year, 1, $status),

            'paid' => EnrolledCoursePaidCache::get($month, $year, $status,1 ,$status),

            'overdue' => PendingPaidEnrolledCourseCache::get($month, $year, 1,$status),

            'certificate_issued' => self::certificateIssued(),

            default => StudentEnrolledCourseCache::get($month, $year, 1, $status),
        };
    }

    protected static function certificateIssued()
    {
        return CertifiedEnrolledCoursesCache::get();
    }

    public static function allCourses()
    {
        return Course::where('is_deleted', 0)->latest()->get();
    }
}
