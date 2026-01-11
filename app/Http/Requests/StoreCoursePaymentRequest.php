<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCoursePaymentRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'student_id' => 'required|exists:crm_students,id',
            'enrolled_course_id' => 'nullable|exists:crm_enrolled_courses,id',
            'paid_amount' => 'required|numeric|min:1',
            'payment_slip' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];
    }
}
