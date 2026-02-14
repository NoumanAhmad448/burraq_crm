<?php

namespace App\Classes;

use App\Models\Student;

class StudentActDel
{
    public static function action($id, $should_delete = 1)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $student = Student::where('id', $id)->first();
        $student->is_deleted = $should_delete;
        $student->save();
    }
}
