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
        // email should not be duplicate except for the current student being edited
        // what would be the logic?
        return [
            'name'          => 'required|string|max:255',
            'father_name'   => 'required|string|max:255',
            'cnic'          => 'required|string|max:25',
            'mobile'        => 'required|string|max:20',
            'email'         => 'nullable|email|max:255',
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'admission_date' => 'required|date',
            'due_date'      => 'required|date|after_or_equal:admission_date',

            'total_fee'     => 'required|numeric|min:0',
            'paid_fee'      => 'required|numeric|min:0|lte:total_fee',

            // 'role'          => 'required|in:employee,hr',

            // Course enrollment (dynamic)
            'courses'                       => 'nullable|array',
            'courses.*.selected'    => 'nullable',
            // 'courses.*.total_fee'    => 'required_with:courses.*.selected|numeric|min:0',
            // 'courses.*.paid_amount'  => 'required_with:courses.*.selected|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'paid_fee.lte' => 'Paid fee cannot be greater than total fee.',
            'due_date.after_or_equal' => 'Due date must be after admission date.',
            'email.unique' => 'This email is already registered with another student.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if (!is_array($this->courses)) {
                return;
            }

            foreach ($this->courses as $index => $course) {

                // 🚫 Skip completely if not selected
                if (empty($course['selected'])) {
                    continue;
                }

                // ✅ Now validate ONLY selected rows
                if (!isset($course['total_fee']) || !is_numeric($course['total_fee'])) {
                    $validator->errors()->add(
                        "courses.$index.total_fee",
                        "Total fee is required and must be numeric."
                    );
                }

                if (!isset($course['paid_amount']) || !is_numeric($course['paid_amount'])) {
                    $validator->errors()->add(
                        "courses.$index.paid_amount",
                        "Paid amount is required and must be numeric."
                    );
                }

                if (
                    isset($course['total_fee'], $course['paid_amount']) &&
                    $course['paid_amount'] > $course['total_fee']
                ) {
                    $validator->errors()->add(
                        "courses.$index.paid_amount",
                        "Paid amount cannot exceed total fee."
                    );
                }
            }
        });
    }
}
