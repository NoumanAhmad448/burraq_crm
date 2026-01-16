<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserFormRequest extends FormRequest
{
    public function authorize()
    {
        return true; // all authenticated users can perform this
    }

    public function rules()
    {
        // Get the current user ID if updating
        $userId = $this->route('user')?->id ?? null;

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'password' => $userId ? 'nullable|string|min:6' : 'required|string|min:6',
            'role' => 'required|in:admission_officer,hr_role,admin,print_certificate',
            'is_admin' => 'nullable|boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already in use.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'role.required' => 'Role is required.',
            'role.in' => 'Selected role is invalid.',
        ];
    }

    protected function prepareForValidation()
    {
        // Convert is_admin checkbox to boolean
        // $this->merge([
        //     'is_admin' => $this->has('is_admin') ? 1 : 0,
        // ]);
    }
}
