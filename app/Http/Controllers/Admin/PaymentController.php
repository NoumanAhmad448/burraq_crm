<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use App\Models\EnrolledCourse;

class PaymentController extends Controller
{
    public function store(StorePaymentRequest $request)
    {
        Payment::create($request->only([
            'enrolled_course_id',
            'amount',
            'payment_date',
            'method',
            'note'
        ]));

        return back()->with('success', 'Payment added');
    }
    public function createPayment($enrolledCourseId)
    {
        $enrolledCourse = EnrolledCourse::with(['course', 'payments', 'student'])
            ->findOrFail($enrolledCourseId);

        $totalPaid = $enrolledCourse->payments->sum('amount');

        return view('admin.students.payment_create', compact(
            'enrolledCourse',
            'totalPaid'
        ));
    }
    public function storePayment(Request $request)
    {
        $request->validate([
            'enrolled_course_id' => 'required|exists:crm_enrolled_courses,id',
            'amount' => 'required|numeric|min:1',
            'paid_at' => 'required|date',
        ]);

        $enrolledCourse = EnrolledCourse::with(['course', 'payments'])
            ->findOrFail($request->enrolled_course_id);

        $alreadyPaid = $enrolledCourse->payments->sum('amount');
        $courseFee = $enrolledCourse->course->fee;

        if (($alreadyPaid + $request->amount) > $courseFee) {
            return back()->withErrors([
                'amount' => 'Payment exceeds total course fee'
            ]);
        }

        Payment::create([
            'enrolled_course_id' => $enrolledCourse->id,
            'amount' => $request->amount,
            'paid_at' => $request->paid_at,
        ]);

        return redirect()
            ->route('students.course.detail', [
                $enrolledCourse->student_id,
                $enrolledCourse->id
            ])
            ->with('success', 'Payment added successfully');
    }
}
