<?php

namespace App\Http\Controllers\Admin;

use App\Classes\ConfirmCompletedStatus;
use App\Classes\LyskillsCarbon;
use App\Classes\StudentActDel;
use App\Classes\StudentForm;
use App\Classes\StudentPayment;
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
            $year,
            $status,
        );

        $all_courses = StudentEnrolledCourseResolver::allCourses();
        return view('admin.students.index', compact('enrolledCourses', 'all_courses', "month", "year"));
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
            $student = StudentForm::studentForm($request);

            /* ---------- STUDENT CREATE ---------- */


            // dd($student);
            // dd($request->name);
            /* ---------- ENROLL COURSES ---------- */
            $this->updateEnrolledCourses($request, $student);


            DB::commit();

            if ($student && $student->status == "Completed") {
                $confirm = new ConfirmCompletedStatus($student->id);
                $enrolledCourses = $confirm->handle();
                if ($enrolledCourses->isNotEmpty()) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Student has not completed all payments.');
                }
            }

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
            } else {
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
                    StudentPayment::payment($courseData, $enrolled_course, $student, $request);
                }
            }

            StudentPayment::delPreCourse($currentEnrolledCourseIds, $student);
        }
    }
    /**
     * Update student
     */
    public function update(StudentStoreRequest $request, $id)
    {
        DB::beginTransaction();

        $student = Student::findOrFail($id);
        StudentForm::studentForm($request, true, $student);

        $this->updateEnrolledCourses($request, $student);
        if ($student && $request->status == "Completed") {
            // dd($student->status);
            $confirm = new ConfirmCompletedStatus($id);

            $enrolledCourses = $confirm->handle();
            // dd($enrolledCourses);
            if ($enrolledCourses->isNotEmpty()) {
                DB::rollback();
                return back()->with('error', 'Student has not completed all payments.');
            }
        }
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
        StudentActDel::action($id);
        return redirect()->back()->with('success', 'Student deleted successfully');
    }
    /**
     * Soft delete student (ADMIN ONLY)
     */
    public function activate($id)
    {
        StudentActDel::action($id, 0);

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
}
