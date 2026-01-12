<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HrUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $userId = $this->route('user')->id;

        return [
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email,' . $userId,

            'mobile_no'       => 'required|string|max:20',
            'father_name'     => 'required|string|max:255',
            'cnic'            => 'required|string|max:25',
            'guardian_name'   => 'nullable|string|max:255',
            'home_address'    => 'nullable|string|max:500',
            'photo'      => 'nullable|file|mimes:jpg,jpeg,png',

            'cnic_photo'      => 'nullable|file|mimes:jpg,jpeg,png',
            'resume'          => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'other_document'  => 'nullable|file|mimes:pdf,jpg,jpeg,png',
        ];
    }
}
