<?php

namespace App\Observers;

use App\Models\EnrolledCoursePayment;
use App\Models\EnrolledCoursePaymentLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class EnrolledCoursePaymentObserver
{
    private function actor()
    {
        $user = Auth::user();

        return [
            'performed_by'       => $user?->id,
            'performed_by_name'  => $user?->name,
            'performed_by_email'=> $user?->email,
            'performed_by_role' => $user?->role ?? null,
            'ip_address'         => Request::ip(),
            'user_agent'         => Request::header('User-Agent'),
        ];
    }

    /**
     * CREATED
     */
    public function created(EnrolledCoursePayment $payment)
    {
        EnrolledCoursePaymentLog::create(array_merge([
            'enrolled_course_payment_id' => $payment->id,
            'action'   => 'created',
            'old_data' => null,
            'new_data' => json_encode($payment->toArray()),
        ], $this->actor()));
    }

    /**
     * UPDATED / DELETED (logical)
     */
    public function updating(EnrolledCoursePayment $payment)
    {
        // LOGICAL DELETE
        if ($payment->isDirty('is_deleted') && $payment->is_deleted == 1) {
            EnrolledCoursePaymentLog::create(array_merge([
                'enrolled_course_payment_id' => $payment->id,
                'action'   => 'deleted',
                'old_data' => json_encode($payment->getOriginal()),
                'new_data' => json_encode(['is_deleted' => 1]),
            ], $this->actor()));

            return;
        }

        // NORMAL UPDATE
        EnrolledCoursePaymentLog::create(array_merge([
            'enrolled_course_payment_id' => $payment->id,
            'action'   => 'updated',
            'old_data' => json_encode($payment->getOriginal()),
            'new_data' => json_encode($payment->getDirty()),
        ], $this->actor()));
    }
}
