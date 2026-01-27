<?php

namespace App\Http\Response;

use App\Classes\EnrolledCourseDue;
use App\Classes\EnrolledCourseDueThisMonth;
use App\Classes\EnrolledCoursePaymentsThisMonth;
use App\Classes\EnrolledCoursePaymentTotal;
use App\Classes\EnrolledCoursePaymentYearly;
use App\Classes\EnrolledCoursePendingThisMonth;
use App\Classes\EnrolledCourseTotalFee;
use App\Classes\EnrolledCourseTotalOverdue;
use App\Classes\EnrolledCourseTotalPaid;
use App\Classes\EnrolledCourseWithCertificate;
use App\Classes\LyskillsCarbon;
use App\Classes\EnrolledCourseTotalOverdueCount;
use App\Classes\EnrolledCourseTotalPaidMonth;
use App\Classes\EnrolledCourseTotalUnpaid;
use App\Classes\EnrolledCourseTotalUnpaidCount;
use App\Classes\StudentCache;
use App\Classes\StudentMonthYearCache;
use App\Classes\StudentYearly;
use App\Http\Contracts\IndexContracts;
use App\Models\Setting;
use Exception;
use App\Models\Student;
use App\Models\Course;
use App\Models\EnrolledCourse;
use Carbon\Carbon;

class IndexResponse implements IndexContracts
{
    public function toResponse($request)
    {
        try {

        $settings = Setting::first();

        extract(parseMonthYear($request));

        // dd($startOfMonth->format("Y-m-d"));

        /* ---------- Students This Month ---------- */
        $studentsThisMonth = StudentCache::studentsThisMonth($month, $year);

        /* ---------- Students Yearly ---------- */
        $studentsYearly = StudentYearly::get($month, $year);

        /* ---------- Payments This Month ---------- */

        $dueThisMonth = EnrolledCourseDue::get($month, $year);

        /* ---------- Annual Payments ---------- */
        $annualPayments = EnrolledCoursePaymentYearly::get($month, $year);


        // Get cached total fee
        $totalFee = EnrolledCourseTotalFee::get();

        // Get cached payments for this month
        $paymentsThisMonth = EnrolledCoursePaymentsThisMonth::get($startOfMonth, $endOfMonth);


        $totalPaid_g = EnrolledCoursePaymentTotal::get();

        $totalPaid = EnrolledCourseTotalPaid::getTotalPaidMonth($startOfMonth, $endOfMonth);


        $pending   = max($totalFee - $totalPaid_g, 0);

        $totalStudents = Student::count();
        $activeStudents = StudentMonthYearCache::get($month, $year);

        $activeEnrolledStudents = Student::where('is_deleted', 0)
            ->whereHas('enrolledCourses')
            ->count();

        // Courses
        $totalCourses = Course::count();
        $activeCourses = Course::where('is_deleted', 0)->count();

        $activeCourses = Course::whereHas('enrolledCourses')->count();

        $totalUnpaid = EnrolledCourseTotalUnpaid::get();

        $totalUnpaid_count = EnrolledCourseTotalUnpaidCount::get();

        $pendingThisMonth = EnrolledCoursePendingThisMonth::get($startOfMonth, $endOfMonth);

        $totalOverdue = EnrolledCourseTotalOverdue::get();

        $totalOverdue_count = EnrolledCourseTotalOverdueCount::get();


         $dueThisMonth = EnrolledCourseDueThisMonth::get($startOfMonth, $endOfMonth);

        $enrolledCourses = EnrolledCourseWithCertificate::get();

        $cert_count = $enrolledCourses->count();

        $total_income = EnrolledCourse::totalIncome();
        $totalPaid_m = EnrolledCourseTotalPaidMonth::get($startOfMonth, $endOfMonth);

            return $request->wantsJson()
                ? response()->json([
                    // ResponseKeys::TITLE => $title,
                    // ResponseKeys::DESC => $desc,
                    // ResponseKeys::CS => $cs,
                    // 'post' => $post,
                    // 'faq' => $faq,
                    // 'courses' => $courses,
                    // "settings" => $settings,
                    // "RatingModal" => $RatingModal
                ])
                :  view(
                    config('setting.welcome_blade', 'dashboard.welcome'),
                    compact(
                        'settings',
                        'totalStudents',
                        'activeStudents',
                        'totalCourses',
                        'activeCourses',
                        'activeEnrolledStudents',
                        'studentsThisMonth',
                        'studentsYearly',
                        'paymentsThisMonth',
                        'annualPayments',
                        'totalFee',
                        'totalPaid_g',
                        'totalPaid',
                        'totalOverdue',
                        'totalUnpaid',
                        'cert_count',
                        'pending',
                        'pendingThisMonth',
                        'dueThisMonth',
                        'month',
                        'year',
                        'totalPaid_m',
                        'totalOverdue_count',
                        'totalUnpaid_count',
                        'total_income',
                    )
                );

        } catch (Exception $e) {
            dd($e->getMessage());
            server_logs([true, $e], [true, $request]);
            return back()->with(["error" => "something went wrong"]);
        }
    }
}
