<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
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

            'role'          => 'required|in:employee,hr',
        ];
    }
}
