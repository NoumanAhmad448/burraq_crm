<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InquiryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'note' => 'nullable|string',
            'status' => 'required|in:pending,resolved,other',
            'course_id' => 'nullable|exists:crm_courses,id',
        ];
    }
}
