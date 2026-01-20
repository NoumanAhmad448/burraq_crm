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
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{


    /**
     * Show create form + students list
     */
    public function index(Request $request)
    {
        // dd($request->all());
        $type = $request->get('type');
        $month = $request->get('month');
        $year = $request->get('year');

        if(!empty($month)){
            $month = LyskillsCarbon::create()->month($month)->month;
        }
        if(!empty($year)){
            $year = LyskillsCarbon::create()->year($year)->year;
        }

        if ($type == 'deleted') {
            $enrolledCourses = EnrolledCourse::with('student', 'payments')
                ->whereHas('student', function ($query) {
                    $query->where('is_deleted', 1);
                })

                 ->latest('created_at')
                ->get();
        }
        else if ($type == 'unpaid') {

            $enrolledCourses = EnrolledCourse::with('student', 'payments')
                ->whereHas('student', function ($query) {
                    $query->where('is_deleted', 0);
                })            ->where('is_deleted', 0)

                ->get()
                ->filter(function ($enrolledCourse) {
                    $totalPaid = $enrolledCourse->payments()->where('is_deleted', 0)->sum('paid_amount');
                    return $totalPaid < $enrolledCourse->total_fee;
                })
                 ->sortByDesc('created_at')
                ->values();

        }
        else if ($type == 'paid') {

            $enrolledCourses = EnrolledCourse::with('student', 'payments')
                ->whereHas('student', function ($query) {
                    $query->where('is_deleted', 0);
                })
                ->get()
                ->filter(function ($enrolledCourse) {
                    $totalPaid = $enrolledCourse->payments()->where('is_deleted', 0)->sum('paid_amount');
                    return $totalPaid >= $enrolledCourse->total_fee;
                })
                            ->where('is_deleted', 0)

                 ->sortByDesc('created_at')
                ->values();

        }
        else if ($type == 'overdue') {
            $enrolledCourses = EnrolledCourse::pendingCourses()->with('student', 'payments')
             ->whereHas('student', function ($query) {
                    $query->where('is_deleted', 0);
                })
                ->activeCourse()
            ->paidStudentsOnly()
             ->latest()
            ->get();
        }
        else if ($type === 'certificate_issued') {
           $enrolledCourses = EnrolledCourse::with([
                'student',
                'payments',
                'certificate' => fn ($q) => $q->where('generated_count', '>', 0)
            ])
            ->whereHas('certificate', fn ($q) =>
                $q->whereNotNull('generated_count')
                ->where('generated_count', '>', 0)
            )
            ->whereHas('student', fn ($q) => $q->where('is_deleted', 0))
                        ->where('is_deleted', 0)

            ->latest()
            ->get();

        }
        else {
        $enrolledCourses = EnrolledCourse::with('student', 'payments')
            ->whereHas('student', fn ($q) => $q->where('is_deleted', 0))
            ->where('is_deleted', 0)
            ->latest()
            ->get();
        }

        // dd($enrolledCourses);
        $all_courses = Course::where('is_deleted', 0)->get();

        return view('admin.students.index', compact('enrolledCourses', 'all_courses', "month", "year"));
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
        if(!empty($request->total_fee) || !empty($request->paid_fee)){
            $remainingFee = $request->total_fee - $request->paid_fee;
        }
        $data = [
            'name'           => $request->name,
            'father_name'    => $request->father_name,
            'cnic'           => $request->cnic,
            'mobile'         => $request->mobile,
            'email'          => $request->email,
        ];

        if(!empty($request->admission_date)){
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

         $request->validate([
            'email' => 'required|email|unique:crm_students,email',
        ]);
         $request->validate([
            'cnic' => 'required|unique:crm_students,cnic',
        ]);

        try {

            /* ---------- IMAGE UPLOAD (STRICTLY AS PROVIDED) ---------- */
            // $courses = Course::where('is_deleted', 0)->get();
            $student = $this->studentForm($request);

            /* ---------- STUDENT CREATE ---------- */


            // dd($student);
            // dd($request->courses);
            /* ---------- ENROLL COURSES ---------- */
            $this->updateEnrolledCourses($request, $student);


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
        // dd("here");
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

        // dd($student);
        $all_courses = Course::all();

        // dd($student);
        return view('admin.students.edit', compact('student', 'all_courses'));
    }

    private function updateEnrolledCourses($request, $student)
    {

        // Logic to update enrolled courses can be added here
        if ($request->has('courses')) {
            // dd($request->courses);
            $currentEnrolledCourseIds = [];

            foreach ($request->courses as $courseId => $courseData) {

                if (array_key_exists("selected", $courseData) && $courseData['selected']) {
                    // dd($courseData);
                    $enrolled_course = null;
                    if (array_key_exists("CEId", $courseData) && $courseData['CEId']) {
                        $enrolled_course = EnrolledCourse::find($courseData['CEId']);
                    }
                    if ($enrolled_course) {
                        $enrolled_course?->update([
                            'student_id' => $student->id,
                            'course_id'  => $courseData["course_id"],
                            'total_fee'  => $courseData['total_fee'],
                            'admission_date' => $courseData['admission_date'],
                            'due_date' => $courseData['due_date'],
                                                'is_deleted'  => 0,

                        ]);
                    } else {
                        $enrolled_course = EnrolledCourse::create([
                            'student_id' => $student->id,
                            'course_id'  => $courseData["course_id"],
                            'total_fee'  => $courseData['total_fee'],
                            'admission_date' => $courseData['admission_date'],
                            'due_date' => $courseData['due_date'],
                        ]);
                    }

                    /* Keep track of valid courses */
                    $currentEnrolledCourseIds[] = $enrolled_course->id;
                    // dd($enrolled_course);
                    /* ---------- PAYMENT AGAINST ENROLLED COURSE ---------- */
                    if (!empty($courseData['paid_amount']) && $courseData['paid_amount'] > 0 && $enrolled_course) {
                        if (array_key_exists("payId", $courseData) && $courseData['payId'] && EnrolledCoursePayment::find($courseData['payId'])) {
                            EnrolledCoursePayment::find($courseData['payId'])?->update(
                                [
                                    'paid_amount' => $courseData['paid_amount'],
                                    'paid_at' => now(),
                                    'payment_by' => auth()->user()->id,
                                    'payment_slip_path'  => $student->payment_slip_path,
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

            if(!empty($currentEnrolledCourseIds)){
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
    /**
     * Update student
     */
    public function update(StudentStoreRequest $request, $id)
    {
         DB::beginTransaction();

        $student = Student::findOrFail($id);
        $this->studentForm($request, true, $student);

        $this->updateEnrolledCourses($request, $student);

        DB::commit();

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
        // dd("here");
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        Student::where('id', $id)->update(['is_deleted' => 1]);
        // dd(Student::where('id', $id)->first());

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
