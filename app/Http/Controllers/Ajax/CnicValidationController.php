<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CnicValidationController extends Controller
{
    public function check(Request $request)
    {
        // 1. Base validation
        try {
            $request->validate([
                'cnic' => ['nullable', 'regex:/^\d{5}-\d{7}-\d{1}$/'],
                'student_id' => ['nullable', 'integer', 'exists:crm_students,id'],
            ], [
                'cnic.regex' => 'CNIC format must be xxxxx-xxxxxxx-x',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'type'    => 'error',
                'message' => $e->validator->errors()->first(),
            ], 422);
        }

        // 2. Normalize CNIC
        $numericCnic = str_replace('-', '', $request->cnic);

        // 3. Check existence excluding current student (update-safe)
        $query = Student::whereRaw(
            "REPLACE(cnic, '-', '') = ?",
            [$numericCnic]
        );

        if ($request->filled('student_id')) {
            $query->where('id', '!=', $request->student_id);
        }

        if ($query->exists()) {
            return response()->json([
                'type'    => 'warning',
                'message' => '⚠ This CNIC already exists for another student name '. $query->name. " and CNIC ". $query->cnic,
            ], 409);
        }

        // 4. All good
        return response()->json([
            'type'    => 'success',
            'message' => 'CNIC is valid and available ✔',
        ]);
    }
}
