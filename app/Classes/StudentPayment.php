<?php

namespace App\Classes;

use App\Classes\ConfirmCompletedStatus;
use App\Classes\LyskillsCarbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StudentStoreRequest;
use App\Mail\StudentFeeReceiptMail;
use App\Models\Student;
use App\Models\Course;
use App\Models\EnrolledCourse;
use App\Models\EnrolledCoursePayment;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Services\StudentEnrolledCourseResolver;

class StudentPayment
{
    public static function payment($courseData, $enrolled_course, $student, $request)
    {
        if (!empty($courseData['paid_amount']) && $courseData['paid_amount'] > 0 && $enrolled_course) {
            if (array_key_exists("payId", $courseData) && $courseData['payId'] && EnrolledCoursePayment::find($courseData['payId'])) {
                EnrolledCoursePayment::find($courseData['payId'])?->update(
                    [
                        'paid_amount' => $courseData['paid_amount'],
                        'paid_at' => now(),
                        'payment_by' => auth()->user()->id,
                        'payment_slip_path'  => $student->payment_slip_path,
                        'payment_date' => $request->payment_date,
                        'payment_method' => $request->payment_method,
                    ]
                );
            } else {
                EnrolledCoursePayment::create([
                    'enrolled_course_id' => $enrolled_course?->id,
                    'paid_amount'        => $courseData['paid_amount'],
                    'paid_at'            => LyskillsCarbon::now(),
                    'payment_by'         => auth()->user()->id,
                    'payment_slip_path'  => $student->payment_slip_path,
                    'payment_date' => $request->payment_date,
                    'payment_method' => $request->payment_method,

                ]);
            }
        }
    }

    public static function delPreCourse($currentEnrolledCourseIds, $student)
    {
        if (!empty($currentEnrolledCourseIds)) {
            EnrolledCourse::where('student_id', $student->id)
                ->whereNotIn('id', $currentEnrolledCourseIds)
                ->update([
                    'is_deleted'  => 1,
                    'deleted_by'  => auth()->id(),
                    'deleted_at'  => now(),
                ]);
        }
    }
}
