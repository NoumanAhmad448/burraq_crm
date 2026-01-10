<?php

namespace App\Http\Controllers;

use App\Models\Student as CrmStudent;
use App\Models\EnrolledCourse;

class StudentCoursePaymentController extends Controller
{
    public function index(CrmStudent $student)
    {
        $enrolledCourses = EnrolledCourse::with([
                'course',
                'payments.paidBy'
            ])
            ->where('student_id', $student->id)
            ->get();

        return view('students.course-payments', compact('student', 'enrolledCourses'));
    }
}
