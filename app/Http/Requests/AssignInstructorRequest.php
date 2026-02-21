<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignInstructorRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules() {
        return [
            'instructors' => 'required|array',
            'group_id' => 'required',
            'instructors.*' => 'exists:users,id'
        ];
    }
}