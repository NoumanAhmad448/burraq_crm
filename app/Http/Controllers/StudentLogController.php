<?php

namespace App\Http\Controllers;

use App\Models\Student as CrmStudent;
use App\Models\CrmStudentLog;

class StudentLogController extends Controller
{
    public function index(CrmStudent $student)
    {
        $logs = CrmStudentLog::where('crm_student_id', $student->id)
            ->orderByDesc('logged_at')
            ->get();

        return view('students.logs', compact('student', 'logs'));
    }
}
