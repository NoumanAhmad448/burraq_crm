<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EnrolledCourse;

class StudentCourseController extends Controller
{
    public function show(EnrolledCourse $enrolledCourse)
    {
        $enrolledCourse->load([
            'student',
            'course',
            'payments'
        ]);

        return view('admin.students.course_detail', compact('enrolledCourse'));
    }
}
