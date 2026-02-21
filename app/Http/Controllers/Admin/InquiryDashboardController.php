<?php

namespace App\Http\Controllers\Admin;

use App\Classes\StartEndDateFilter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\EnrolledCourse;
use App\Classes\LyskillsCarbon;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class InquiryDashboardController extends Controller
{
    // Helper function to get date range
    function getDateRange($month, $year, $lastMonths, $startDate, $endDate)
    {
        if ($month && $year) {
            $start = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $end   = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        } elseif ($year) {
            $date = Carbon::createFromDate($year, 1, 1);
            $start = $date->copy()->startOfYear();
            $end   = $date->copy()->endOfYear();
        } elseif ($lastMonths) {
            $end   = Carbon::now()->endOfDay();
            $start = Carbon::now()->subMonths($lastMonths)->startOfDay();
        } elseif ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end   = Carbon::parse($endDate)->endOfDay();
        } elseif ($endDate) {
            $start = $end = Carbon::parse($endDate);
        } else {
            $start = $end = null;
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
        [$enrollStart, $enrollEnd] = $this->getDateRange($month, $year, $lastMonths, $startDate, $endDate);

        // Payment filter range
        [$paymentStart, $paymentEnd] = $this->getDateRange($month, $year, $lastMonths, $startDate, $endDate);

        // Lead filter range
        [$leadStart, $leadEnd] = $this->getDateRange($month, $year, $lastMonths, $startDate, $endDate);

        $dateCondition = '';

        if ($enrollStart && $enrollEnd) {
            $dateCondition = " AND ec2.admission_date BETWEEN '{$enrollStart}' AND '{$enrollEnd}' ";
        }

        $dashboardRaw = Course::query()
                ->with(["enrolledCourses",
                    "enrolledCourses.payments" => function($q){
                        $q->active();
                    }
                , "leads"])
                ->whereHas("enrolledCourses", function($q){
                    $q->activeStudentInRelation();
                })
                ->active()
                ->get();

        // dd($dashboardRaw[0]->enrolledCourses->flatMap->payments->first()->totalPaid()
        // ?->reduce(function ($payment) {
        //                             $payment->totalPaid();
        //                         }, 0)
        // );
          
        // dd($courses[0]->leads->count());

        // dd($dashboardRaw);
        // Format for dashboard
        $dashboardData = $dashboardRaw->map(fn($row) => [
            'course_name' => $row->name,
            'course_id'   => $row->course_id,
            'students'    => (int) $row->enrolledCourses()?->activeCourse()?->count() ?? 0,
            'revenue'     => (float) $row->enrolledCourses
                                ?->flatMap?->payments
                                ?->reduce(function ($carry, $payment) {
                                    return $carry + $payment->totalPaid();
                                }, 0)
                            ,
            'leads'       => (int) $row->leads()->count()
        ])->toArray();

        // dd($dashboardData);
        // Limit cards display
        $displayCourses = $dashboardData;

        return view('admin.inquiries.dashboards', compact('dashboardData', "displayCourses"));
    }
}
