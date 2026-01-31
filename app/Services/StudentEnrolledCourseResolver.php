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
    public static function resolve(string|null $type, ?int $month, ?int $year)
    {
        return match ($type) {
            'deleted' => EnrolledCourseStudentFilter::query($month, $year),

            'unpaid' => EnrolledCourseDuePaymentCache::get($month, $year),

            'paid' => EnrolledCoursePaidCache::get($month, $year),

            'overdue' => PendingPaidEnrolledCourseCache::get($month, $year),

            'certificate_issued' => self::certificateIssued(),

            default => StudentEnrolledCourseCache::get($month, $year),
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
