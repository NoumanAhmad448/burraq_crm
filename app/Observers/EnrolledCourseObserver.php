<?php

namespace App\Observers;

use App\Models\EnrolledCourse;
use App\Models\CrmEnrolledCourseLog;

class EnrolledCourseObserver
{
    public function created(EnrolledCourse $enrolledCourse): void
    {
        $this->log($enrolledCourse, 'created');
    }

    public function updated(EnrolledCourse $enrolledCourse): void
    {
        if ($enrolledCourse->isDirty('total_fee')) {
            $this->log($enrolledCourse, 'updated');
        }
    }

    protected function log(EnrolledCourse $enrolledCourse, string $action): void
    {
        CrmEnrolledCourseLog::create([
            'student_id'         => $enrolledCourse->student_id,
            'course_id'          => $enrolledCourse->course_id,
            'enrolled_course_id' => $enrolledCourse->id,
            'user_id'            => auth()->id(),
            'action'             => $action,
            'total_fee'          => $enrolledCourse->total_fee,
            'snapshot'           => $enrolledCourse->toArray(),
        ]);
    }
}
