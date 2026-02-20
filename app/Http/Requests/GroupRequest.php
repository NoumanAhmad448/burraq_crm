<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'group_name' => 'required|string|max:255',
            'timing' => 'nullable|string|max:255',
        ];
    }
}
