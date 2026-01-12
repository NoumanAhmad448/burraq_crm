<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Certificate;
use App\Models\EnrolledCourse;
use App\Models\EnrolledCoursePayment;
use App\Classes\LyskillsCarbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class CertificateController extends Controller
{
    /**
     * Show certificate page
     */
   public function index()
    {
        $enrolledCourses = EnrolledCourse::with([
                'student',
                'course',
                'certificate',
            ])
            ->orderby('created_at', 'desc')
            ->get()
            ->map(function ($enrolledCourse) {

                $totalPaid = EnrolledCoursePayment::where(
                    'enrolled_course_id',
                    $enrolledCourse->id
                )->where("is_deleted", 0)->sum('paid_amount');

                return [
                    'enrolled_course' => $enrolledCourse,
                    'student'         => $enrolledCourse->student,
                    'course'          => $enrolledCourse->course,
                    'total_fee'       => $enrolledCourse->total_fee,
                    'total_paid'      => $totalPaid,
                    'is_paid'         => ($totalPaid == $enrolledCourse->total_fee),
                ];
            });
  //        dd($enrolledCourses);
        return view('admin.certificates.index', compact('enrolledCourses'));
    }


    /**
     * Generate certificate
     */
    public function generate(Request $request, $studentId,$enrolledCourseId)
    {
        $student = Student::findOrFail($studentId);

        $enrolledCourse = EnrolledCourse::findOrFail($enrolledCourseId);

        $totalPaid = EnrolledCoursePayment::where(
            'enrolled_course_id',
            $enrolledCourse->id
        )->sum('paid_amount');

        if ($totalPaid != $enrolledCourse->total_fee) {
            return redirect()->back()
                ->withErrors('Certificate cannot be generated. Payment is incomplete.');
        }

        $certificate = Certificate::create([
            'student_id' => $student->id,
            'enrolled_course_id' => $enrolledCourse->id,
            'generated_by' => auth()->id(),
            'generated_count' => 1,
            'last_generated_at' => LyskillsCarbon::now()
        ]);

        server_logs('Certificate generated', [
            'student_id' => $student->id,
            'enrolled_course_id' => $enrolledCourse->id,
            'generated_by' => auth()->id()
        ]);

        $payments = EnrolledCoursePayment::where('enrolled_course_id', $enrolledCourse->id)
        ->orderBy('created_at', 'asc')
        ->get();

        $data = [
            'student'        => $student,
            'enrolledCourse' => $enrolledCourse,
            'certificate'    => $certificate,
            'issuedDate'     => LyskillsCarbon::now()->toFormattedDateString(),
            'companyName'    => 'Burraq Engineering',
            'payments'       => $payments
        ];


        $pdf = PDF::loadView('admin.certificates.pdf.certificate', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->download(
            'certificate_'.$student->id.'_'.$enrolledCourse->id.'.pdf'
        );
    }

    /**
     * Certificate logs
     */
    public function logs($enrolledCourseId)
    {
        $enrolledCourse = EnrolledCourse::findOrFail($enrolledCourseId);
        $certificates = Certificate::where('enrolled_course_id', $enrolledCourse->id)
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('admin.certificates.log', compact(
            'enrolledCourse',
            'certificates'
        ));
    }
}
