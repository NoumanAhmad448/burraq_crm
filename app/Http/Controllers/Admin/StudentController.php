<?php

namespace App\Http\Controllers\Admin;

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
use Barryvdh\DomPDF\Facade as PDF;
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

    /**
     * Store new student
     */
    public function store(StudentStoreRequest $request)
    {
        DB::beginTransaction();

        try {

            /* ---------- IMAGE UPLOAD (STRICTLY AS PROVIDED) ---------- */
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $img = $request->file('photo');

                $f_name = $img->getClientOriginalName();
                $manager = new ImageManager();
                $image = $manager->make($img)->resize(500, 500);

                $uploadData = new \App\Helpers\UploadData();
                $photoPath = $uploadData->upload(
                    $image->stream()->__toString(),
                    $f_name
                );
            }

            /* ---------- STUDENT CREATE ---------- */
            $remainingFee = $request->total_fee - $request->paid_fee;

            $student = Student::create([
                'name'           => $request->name,
                'father_name'    => $request->father_name,
                'cnic'           => $request->cnic,
                'mobile'         => $request->mobile,
                'email'          => $request->email,
                'photo'          => $photoPath,
                'admission_date' => $request->admission_date,
                'due_date'       => $request->due_date,
                'total_fee'      => $request->total_fee,
                'paid_fee'       => $request->paid_fee,
                'remaining_fee'  => $remainingFee,
            ]);

            // dd($student);
            // dd($request->courses);
            /* ---------- ENROLL COURSES ---------- */
            if ($request->has('courses')) {
                foreach ($request->courses as $courseId => $courseData) {

                    if(array_key_exists("selected", $courseData) && $courseData['selected']) {
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
            dd($e->getMessage());
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
        $student = Student::where('is_deleted', 0)->findOrFail($id);
        $courses = Course::all();

        return view('admin.students.edit', compact('student', 'courses'));
    }

    /**
     * Update student
     */
    public function update(StudentStoreRequest $request, $id)
    {
        $student = Student::findOrFail($id);

        $remainingFee = $request->total_fee - $request->paid_fee;

        $student->update([
            'name'          => $request->name,
            'father_name'   => $request->father_name,
            'mobile'        => $request->mobile,
            'email'         => $request->email,
            'total_fee'     => $request->total_fee,
            'paid_fee'      => $request->paid_fee,
            'remaining_fee' => $remainingFee,
            'role'          => $request->role,
        ]);

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
