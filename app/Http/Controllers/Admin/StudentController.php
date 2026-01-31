<?php

namespace App\Http\Controllers\Admin;

use App\Classes\LyskillsCarbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StudentStoreRequest;
use App\Mail\StudentFeeReceiptMail;
use App\Models\Student;
use App\Models\Course;
use App\Models\EnrolledCourse;
use App\Models\EnrolledCoursePayment;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Services\StudentEnrolledCourseResolver;

class StudentController extends Controller
{
    /**
     * Show create form + students list
     */
    public function index(Request $request)
    {
        // dd($request->all());
        $type = $request->get('type');
        extract(studentMonthYear($request));

        $enrolledCourses = StudentEnrolledCourseResolver::resolve(
            $type,
            $month,
            $year
        );

        $all_courses = StudentEnrolledCourseResolver::allCourses();

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
        // dd($request->registration_date);
        if (!empty($request->registration_date)) {
            $data['registration_date'] = $request->registration_date;
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
            // $courses = Course::where('is_deleted', 0)->get();
            $student = $this->studentForm($request);

            /* ---------- STUDENT CREATE ---------- */


            // dd($student);
            // dd($request);
            /* ---------- ENROLL COURSES ---------- */
            $this->updateEnrolledCourses($request, $student);


            DB::commit();

            // Main recipients
            $toEmails = config("setting.student_emails");

            // Remove empty emails just in case
            $toEmails = array_filter($toEmails);

            // Do not proceed if no valid TO emails
            if (!empty($toEmails) && !empty($student?->email)) {
                $mail = Mail::to($student->email);

                // CC student if email exists
                $mail->cc($toEmails);

                $mail->send(new StudentFeeReceiptMail($student));
            }else{
                Log::warning('Student fee receipt email NOT sent: No primary recipient emails found.', [
                    'student_id' => $student->id ?? null,
                    'student_email' => $student->email ?? null,
                ]);

            }

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
        // dd($request->all());
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
     * Soft delete student (ADMIN ONLY)
     */
    public function activate($id)
    {
        Student::where('id', $id)->update(['is_deleted' => 0]);

        return redirect()->back()->with('success', 'Student activated successfully');
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
