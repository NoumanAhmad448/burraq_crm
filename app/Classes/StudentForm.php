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

class StudentForm
{
    public static function studentForm($request, $is_update = false, $student = null)
    {
        $photoPath = null;
        $payment_slip_path = null;

        if ($request->hasFile('photo')) {
            $img = $request->file('photo');
            $photoPath = uploadPhoto($img);
        }

        if ($request->hasFile('payment_slip_path')) {
            $payment_slip_path = uploadPhoto($request->file('payment_slip_path'));
        }
        if (!empty($request->total_fee) || !empty($request->paid_fee)) {
            $remainingFee = $request->total_fee - $request->paid_fee;
        }
        $data = [
            'name'           => $request->name,
            'father_name'    => $request->father_name,
            'cnic'           => $request->cnic,
            'mobile'         => $request->mobile,
            'email'          => $request->email,
        ];

        if (!empty($request->admission_date)) {
            $data['admission_date'] = $request->admission_date;
        }
        if (!empty($request->due_date)) {
            $data['due_date'] = $request->due_date;
        }

        if (!empty($request->total_fee)) {
            $data['total_fee'] = $request->total_fee;
        }

        if (!empty($request->paid_fee)) {
            $data['paid_fee'] = $request->paid_fee;
        }

        if (isset($remainingFee)) {
            $data['remaining_fee'] = $remainingFee;
        }
        if ($photoPath) {
            $data['photo'] = $photoPath;
        }
        if ($payment_slip_path) {
            $data['payment_slip_path'] = $payment_slip_path;
        }
        // dd($request->registration_date);
        if (!empty($request->registration_date)) {
            $data['registration_date'] = $request->registration_date;
        }
        if (!empty($request->status)) {
            $data['status'] = $request->status;
        }
        if (!empty($request->drop_reason)) {
            $data['drop_reason'] = $request->drop_reason;
        }
        // dump($request);
        // dd($data);

        if ($is_update == false) {
            $student = Student::create($data);
        } else {
            // dd($data);
            $student->update($data);
        }
        // dd($student);
        return $student;
    }

    public static function sendEmail($student)
    {
        // Main recipients
        $ccEmails = config("setting.student_emails");
        // dd($toEmails);
        // Remove empty emails just in case
        $ccEmails = array_filter($ccEmails);
        // Do not proceed if no valid TO emails
        if (!empty($student?->email) && config("app.live_env") == config("app.env")) {
            Mail::to($student->email)
            ->when(!empty($ccEmails), fn ($mail) => $mail->cc($ccEmails))
            ->queue(new StudentFeeReceiptMail($student));
        }
    }
}
