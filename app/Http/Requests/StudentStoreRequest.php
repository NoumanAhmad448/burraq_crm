<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true; // auth middleware already applied
    }

    public function rules()
    {
        return [
            'name'          => 'required|string|max:255',
            'father_name'   => 'required|string|max:255',
            'cnic'          => 'required|string|max:25',
            'mobile'        => 'required|string|max:20',
            'email'         => 'nullable|email|max:255',
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'admission_date'=> 'required|date',
            'due_date'      => 'required|date|after_or_equal:admission_date',

            'total_fee'     => 'required|numeric|min:0',
            'paid_fee'      => 'required|numeric|min:0|lte:total_fee',

            // 'role'          => 'required|in:employee,hr',

            // Course enrollment (dynamic)
            'courses'                       => 'nullable|array',
            'courses.*.selected'            => 'nullable',
            'courses.*.total_fee'           => 'nullable|numeric|min:0',
            'courses.*.paid_amount'         => 'nullable|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'paid_fee.lte' => 'Paid fee cannot be greater than total fee.',
            'due_date.after_or_equal' => 'Due date must be after admission date.',
        ];
    }
}
