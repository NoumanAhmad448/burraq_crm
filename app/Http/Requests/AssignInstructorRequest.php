<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignInstructorRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules() {
        return [
            'instructors' => 'required|array',
            'instructors.*' => 'exists:users,id'
        ];
    }
}