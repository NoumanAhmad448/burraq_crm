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
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|string',
            'note' => 'nullable|string',
            'status' => 'required|in:pending,resolved,other',
            'course_id' => 'required|exists:crm_courses,id',
        ];
    }

    public function prepareForValidation()
    {
        $email = $this->input('email');

        $this->merge([
            'email' => is_string($email) && trim($email) === '' ? null : $email,
        ]);
    }
}
