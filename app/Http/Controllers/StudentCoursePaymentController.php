<?php

namespace App\Http\Controllers;

use App\Models\EnrolledCourse;
use App\Models\EnrolledCoursePayment;
use App\Models\EnrolledCoursePaymentLog;

class StudentCoursePaymentController extends Controller
{
    public function index(EnrolledCoursePayment $payment)
    {
        $enrolledCourses = EnrolledCoursePaymentLog::
            where('enrolled_course_payment_id', $payment->id)
            ->latest()
            ->get();
        return view('students.course-payments', compact('enrolledCourses'));
    }

    public function courseLogs(EnrolledCourse $course){
        // dd($course);
        // EnrolledCourse::where("id", $course->id)->latest()-

        return view('students.all-course-payments', compact('course'));

    }
}
