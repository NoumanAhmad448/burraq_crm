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

            'admission_date' => 'nullable|date',
            'due_date'      => 'nullable|date|after_or_equal:admission_date',

            'total_fee'     => 'nullable|numeric|min:0',
            'paid_fee'      => 'nullable|numeric|min:0|lte:total_fee',

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
                $validator->errors()->add(
                        "courses",
                        "Student must be enrolled in atleast one course."
                    );
            }
            // dd(count($this->courses));
            // dd($this->courses);
            if (is_array($this->courses) && count($this->courses) == 0) {
                $validator->errors()->add(
                        "courses",
                        "Student must be enrolled in atleast one course."
                    );
            }

            $selected_count = 0;
            foreach ($this->courses as $index => $course) {


                // 🚫 Skip completely if not selected
                if (empty($course['selected'])) {
                    continue;
                }

                if(!empty($course['selected'])){
                    $selected_count++;
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

                if (!isset($course['admission_date']) || empty($course['admission_date'])) {
                        $validator->errors()->add(
                            "courses.$index.admission_date",
                            "Course Admission date is required."
                        );
                    }

                    // if (!isset($course['due_date']) || empty($course['due_date'])) {
                    //     $validator->errors()->add(
                    //         "courses.$index.due_date",
                    //         "Course Due date is required."
                    //     );
                    // }

                    // Additional validation for date logic
                    if (isset($course['admission_date']) && isset($course['due_date'])) {
                        if (strtotime($course['due_date']) < strtotime($course['admission_date'])) {
                            $validator->errors()->add(
                                "courses.$index.due_date",
                                "Course Due date must be on or after Course Admission date."
                            );
                        }
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

            if($selected_count == 0){
                $validator->errors()->add('base', 'At least one course needs to be selected and must have all fee details filled.');

            }
        });
    }
}
