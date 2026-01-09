<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'amount' => 'required|numeric|min:1'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $enrolledCourse = \App\Models\EnrolledCourse::find($this->enrolled_course_id);

            if (!$enrolledCourse) {
                $validator->errors()->add('amount', 'Invalid enrolled course.');
                return;
            }

            $totalAfter = $enrolledCourse->totalPaid() + $this->amount;

            if ($totalAfter > $enrolledCourse->course->fee) {
                $validator->errors()->add(
                    'amount',
                    'Payment exceeds course total fee.'
                );
            }
        });
    }
}
