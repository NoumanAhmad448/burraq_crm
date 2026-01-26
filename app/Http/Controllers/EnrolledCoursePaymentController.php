<?php

namespace App\Http\Controllers;

use App\Models\EnrolledCourse;
use App\Models\EnrolledCoursePayment;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCoursePaymentRequest;
use Intervention\Image\ImageManager;

class EnrolledCoursePaymentController extends Controller
{

    public function create()
    {
        $students = Student::all();
        return view('admin.course_payments.create', compact('students'));
    }

    public function paymentsList($student_id, $enrolledCourseId)
    {
                $students = Student::all();

        $payments = EnrolledCoursePayment::where('enrolled_course_id', $enrolledCourseId)
            ->orderBy('created_at', 'desc')
            ->get();

        $enrolledCourse = EnrolledCourse::find($enrolledCourseId);
        $enrolled_course_id = $enrolledCourseId;
        return view('admin.course_payments.create', compact('payments', 'student_id', 'enrolled_course_id', 'enrolledCourse', "students"));
    }
    public function get($student_id, $enrolledCourseId, $payment_id)
    {
                $students = Student::all();

        $payments = EnrolledCoursePayment::where('enrolled_course_id', $enrolledCourseId)
            ->orderBy('created_at', 'desc')
            ->get();

        $enrolledCourse = EnrolledCourse::find($enrolledCourseId);
        $enrolled_course_id = $enrolledCourseId;

        $payment = EnrolledCoursePayment::find($payment_id);
        return view('admin.course_payments.edit', compact('payments', 'student_id', 'enrolled_course_id', 'enrolledCourse', "students",
                    "payment"
        ));
    }


    public function store(StoreCoursePaymentRequest $request)
    {
        // dd($request->all());
        $data = $request->validated();

        // dd($data);
        // Handle file upload
        $paymentSlipPath = null;
        if ($request->hasFile('payment_slip')) {
            $img = $request->file('payment_slip');
            $paymentSlipPath = uploadPhoto($img);
        }

        // dd($paymentSlipPath);
        // Determine enrolled course
        $enrolledCourse = null;
        if (!empty($data['enrolled_course_id'])) {
            $enrolledCourse = EnrolledCourse::find($data['enrolled_course_id']);
            // dd($enrolledCourse);
        } else {
            // If no enrolled course, create one
            $enrolledCourse = EnrolledCourse::firstOrCreate(
                ['student_id' => $data['student_id'], 'course_id' => $data['course_id'] ?? 0],
                ['total_fee' => $data['paid_amount']]
            );
        }

        // dd($request->all());

        $data = [
                'paid_amount' => $data['paid_amount'],
                'payment_by' => auth()->id(),
                'payment_slip_path' => $paymentSlipPath,
                'paid_at' => now(),
                "student_id" => $data['student_id'],
                "enrolled_course_id" => $enrolledCourse->id,
                'payment_date' => $data["payment_date"],
                'payment_method' => $data["payment_method"],
            ];

        // dd($request->payment_id);
        // Create or update payment
        if($request?->payment_id){
            $payment = EnrolledCoursePayment::find($request->payment_id);
            $payment->update($data);
        }else{
            EnrolledCoursePayment::create($data);
        }

        return redirect()->back()->with('success', 'Payment recorded successfully.');
    }

    // Update Payment
    public function update(Request $request, EnrolledCoursePayment $payment)
    {
        // Only allow updating paid_amount and payment_slip_path
        $payment->paid_amount = $request->input('paid_amount', $payment->paid_amount);

        if ($request->hasFile('payment_slip')) {
            $img = $request->file('payment_slip');
            $payment->payment_slip_path = uploadPhoto($img);
        }
        $payment->payment_by = auth()->id();
        $payment->paid_at = now();

        $payment->payment_date = $request->payment_date;
        $payment->payment_method = $request->payment_method;

        $payment->save();

        return redirect()->back()->with('success', 'Payment updated successfully.');
    }

    // Soft Delete Payment
    public function destroy(EnrolledCoursePayment $payment)
    {
        // dd($payment);
        $payment->is_deleted = true;
        $payment->save();

        return redirect()->back()->with('success', 'Payment deleted successfully.');
    }
}
