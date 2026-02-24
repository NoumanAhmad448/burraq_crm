<?php

namespace App\Http\Controllers\Admin;

use App\Classes\StartEndDateFilter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\EnrolledCourse;
use App\Classes\LyskillsCarbon;
use App\Models\EnrolledCoursePayment;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class InquiryDashboardController extends Controller
{
    // Helper function to get date range
    public static function getDateRange($month, $year, $lastMonths, $startDate, $endDate)
    {
        $start = null;
        $end   = null;

        if ($month && $year) {

            $base  = Carbon::createFromDate($year, $month, 1);
            $start = $base->copy()->startOfMonth();
            $end   = $base->copy()->endOfMonth();
        } elseif ($year) {

            $base  = Carbon::createFromDate($year, 1, 1);
            $start = $base->copy()->startOfYear();
            $end   = $base->copy()->endOfYear();
        } elseif ($lastMonths) {

            $end   = now()->endOfDay();
            $start = now()->subMonths($lastMonths)->startOfDay();
        } elseif ($startDate || $endDate) {

            $start = $startDate
                ? Carbon::parse($startDate)->startOfDay()
                : Carbon::parse($endDate)->startOfDay();

            $end = $endDate
                ? Carbon::parse($endDate)->endOfDay()
                : Carbon::parse($startDate)->endOfDay();
        }
        // dump($start);
        // dd($end);
        return [$start, $end];
    }

    public function index(Request $request)
    {
        // Get filters from request
        $month       = $request->month;
        $year        = $request->year;
        $lastMonths  = $request->last_months;
        $startDate   = $request->start_date;
        $endDate     = $request->end_date;
        $courseId    = $request->course_id ?? null;

        // Enrollment filter range
        [$enrollStart, $enrollEnd] = self::getDateRange($month, $year, $lastMonths, $startDate, $endDate);
        // dd($enrollStart);
        // Payment filter range
        [$paymentStart, $paymentEnd] = [$enrollStart, $enrollEnd];

        // Lead filter range
        [$leadStart, $leadEnd] = [$enrollStart, $enrollEnd];

        $dashboardRaw = Course::query()
            // Leads count
            ->withCount([
                'leads as leads_count' => function ($q) use ($leadStart, $leadEnd) {
                    $q->whereNotSoftDeleted()->when($leadStart && $leadEnd, function ($q) use ($leadStart, $leadEnd) {
                        $q->dateFilter($leadStart, $leadEnd, 'created_at', "crm_inquiries");
                    });
                }
            ])

            // Enroll count
            ->withCount([
                'enrolledCourses as enroll_count' => function ($q) use ($enrollStart, $enrollEnd) {
                    $q->activeStatus()->whereNotDeleted()
                        ->whereHas('student', function ($q) {
                            $q->active();
                        })
                        ->whereHas('payments', function ($q) {
                            $q->active();
                        })
                        ->when($enrollStart && $enrollEnd, function ($q) use ($enrollStart, $enrollEnd) {
                            $q->dateFilter($enrollStart, $enrollEnd, "admission_date", "crm_enrolled_courses");
                        });
                }
            ])

            // Payment sum (CASE logic)
            ->selectSub(
                \App\Models\EnrolledCoursePayment::query()
                    ->selectRaw("
                        COALESCE(SUM(
                            CASE 
                                WHEN type = 'refunded' THEN -paid_amount 
                                ELSE paid_amount 
                            END
                        ), 0)
                    ")
                    ->whereHas('enrolledCourse', function ($q) {
                        $q->whereColumn('crm_enrolled_courses.course_id', 'crm_courses.id');
                    })
                    ->when($paymentStart && $paymentEnd, function ($q) use ($paymentStart, $paymentEnd) {
                        $q->dateFilter($paymentStart, $paymentEnd, 'payment_date', "crm_course_payments");
                    })
                    ->active(),
                'total_payment'
            )
            ->active()

            ->get();
        // dd($dashboardRaw[0]->total_payment);
        // Format for dashboard
        $dashboardData = $dashboardRaw->map(fn($row) => [
            'course_name' => $row->name,
            'course_id'   => $row->id,
            'students'    => (int) $row->enroll_count ?? 0,
            'revenue'     => (float) $row->total_payment ?? 0,
            'leads'       => (int) $row->leads_count
        ])->toArray();

        // dd($dashboardData);
        // Limit cards display
        $displayCourses = $dashboardData;

        return view('admin.inquiries.dashboards', compact('dashboardData', "displayCourses"));
    }
}
