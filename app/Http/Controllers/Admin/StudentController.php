<?php

namespace App\Http\Controllers\Admin;

use App\Classes\LyskillsCarbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StudentStoreRequest;
use App\Models\Student;
use App\Models\Course;
use App\Models\EnrolledCourse;
use App\Models\EnrolledCoursePayment;
use App\Models\Payment;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    /**
     * Show create form + students list
     */
    public function index()
    {
        $students = Student::where('is_deleted', 0)
            ->with('enrolledCourses.course')
            ->latest()
            ->get();

        $courses = Course::where('is_deleted', 0)->get();

        return view('admin.students.index', compact('students', 'courses'));
    }

    private function studentForm($request, $is_update = false, $student = null)
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
        $remainingFee = $request->total_fee - $request->paid_fee;
        $data = [
            'name'           => $request->name,
            'father_name'    => $request->father_name,
            'cnic'           => $request->cnic,
            'mobile'         => $request->mobile,
            'email'          => $request->email,
            'admission_date' => $request->admission_date,
            'due_date'       => $request->due_date,
            'total_fee'      => $request->total_fee,
            'paid_fee'       => $request->paid_fee,
            'remaining_fee'  => $remainingFee,
        ];
        if ($photoPath) {
            $data['photo'] = $photoPath;
        }
        if ($payment_slip_path) {
            $data['payment_slip_path'] = $payment_slip_path;
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
    /**
     * Store new student
     */
    public function store(StudentStoreRequest $request)
    {
        DB::beginTransaction();

        try {

            /* ---------- IMAGE UPLOAD (STRICTLY AS PROVIDED) ---------- */
            $courses = Course::where('is_deleted', 0)->get();
            $student = $this->studentForm($request);

            /* ---------- STUDENT CREATE ---------- */


            // dd($student);
            // dd($request->courses);
            /* ---------- ENROLL COURSES ---------- */
            if ($request->has('courses')) {
                foreach ($request->courses as $courseId => $courseData) {

                    if (array_key_exists("selected", $courseData) && $courseData['selected']) {
                        $enrolled = EnrolledCourse::create([
                            'student_id' => $student->id,
                            'course_id'  => $courseId,
                            'total_fee'  => $courseData['total_fee'],
                        ]);

                        /* ---------- PAYMENT AGAINST ENROLLED COURSE ---------- */
                        if (!empty($courseData['paid_amount']) && $courseData['paid_amount'] > 0) {
                            EnrolledCoursePayment::create([
                                'enrolled_course_id' => $enrolled->id,
                                'paid_amount'        => $courseData['paid_amount'],
                                'paid_at'       => now(),
                                'payment_by'         => auth()->user()->id,
                                'payment_slip_path'  => $student->payment_slip_path,
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            /* ---------- CHECKBOX LOGIC ---------- */
            if ($request->print) {
                return redirect()
                    ->route('students.print', $student->id)
                    ->with('success', 'Student created successfully');
            }

            if (!$request->continue_add) {
                return redirect()->route('students.edit', $student->id);
            }

            return redirect()->back()->with('success', 'Student created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            // server_logs('Student Create Error', $e->getMessage());
            Log::error($e->getMessage());
            // dd($e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Show student detail (same page / ajax)
     */
    public function show($id)
    {
        $student = Student::with('enrolledCourses.course', 'enrolledCourses.payments')
            ->findOrFail($id);

        return view('admin.students.partials.student_detail', compact('student'));
    }

    /**
     * Edit student
     */
    public function edit($id)
    {
        $student = Student::where('is_deleted', 0)
            ->with([
                'enrolledCourses.payments'
            ])
            ->findOrFail($id);

        $courses = Course::all();

        // dd($student);
        return view('admin.students.edit', compact('student', 'courses'));
    }

    /**
     * Update student
     */
    public function update(StudentStoreRequest $request, $id)
    {
        $student = Student::findOrFail($id);
        $this->studentForm($request, true, $student);

        if ($request->has('courses')) {
            // dd($request->courses);
            foreach ($request->courses as $courseId => $courseData) {

                if (array_key_exists("selected", $courseData) && $courseData['selected']) {
                    // dd($courseData);
                    $enrolled_course = EnrolledCourse::find($courseData['CEId']);
                    if ($enrolled_course) {
                        $enrolled_course?->update([
                            'student_id' => $student->id,
                            'course_id'  => $courseId,
                            'total_fee'  => $courseData['total_fee'],
                        ]);
                    } else {
                        $enrolled_course = EnrolledCourse::create([
                            'student_id' => $student->id,
                            'course_id'  => $courseId,
                            'total_fee'  => $courseData['total_fee'],
                        ]);
                    }
                    // dd($enrolled_course);
                    /* ---------- PAYMENT AGAINST ENROLLED COURSE ---------- */
                    if (!empty($courseData['paid_amount']) && $courseData['paid_amount'] > 0 && $enrolled_course) {
                        if (array_key_exists("payId", $courseData) && $courseData['payId'] && EnrolledCoursePayment::find($courseData['payId'])) {
                            EnrolledCoursePayment::find($courseData['payId'])?->update(
                                [
                                    'paid_amount' => $courseData['paid_amount'],
                                    'paid_at' => now(),
                                    'payment_by' => auth()->user()->id,
                                ]
                            );
                        } else {
                            EnrolledCoursePayment::create([
                                'enrolled_course_id' => $enrolled_course?->id,
                                'paid_amount'        => $courseData['paid_amount'],
                                'paid_at'            => LyskillsCarbon::now(),
                                'payment_by'         => auth()->user()->id,
                                    'payment_slip_path'  => $student->payment_slip_path,

                            ]);
                        }
                    }
                }
            }
        }


        if ($request->print) {
            return redirect()
                ->route('students.print', $student->id)
                ->with('success', 'Student created successfully');
        }
        return redirect()->back()->with('success', 'Student updated successfully');
    }


    /**
     * Soft delete student (ADMIN ONLY)
     */
    public function delete($id)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        Student::where('id', $id)->update(['is_deleted' => 1]);

        return redirect()->back()->with('success', 'Student deleted successfully');
    }

    /**
     * Print student PDF
     */
    public function print($id)
    {
        $student = Student::with('enrolledCourses.course')->findOrFail($id);
        // dd($student);
        $pdf = PDF::loadView('admin.students.print', [
            'student' => $student,
            'company' => 'Burraq Engineering'
        ]);

        return $pdf->stream('student_' . $student->id . '.pdf');
    }

    public function courseDetail($studentId, $enrolledCourseId)
    {
        $student = Student::where('id', $studentId)
            ->where('is_deleted', false)
            ->firstOrFail();

        $enrolledCourse = EnrolledCourse::with(['course', 'payments'])
            ->where('id', $enrolledCourseId)
            ->where('student_id', $studentId)
            ->firstOrFail();

        $totalPaid = $enrolledCourse->payments->sum('amount');

        if ($totalPaid > $enrolledCourse->course->fee) {
            server_logs('Payment exceeded course fee', [
                'student_id' => $studentId,
                'enrolled_course_id' => $enrolledCourseId
            ]);
        }

        return view('admin.students.course_detail', compact(
            'student',
            'enrolledCourse',
            'totalPaid'
        ));
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
