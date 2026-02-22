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
                ->with(["enrolledCourses"                    
                , "leads"])
                ->whereHas("enrolledCourses", function($q) use($enrollStart, $enrollEnd){
                    $q->activeCourse()->activeStudentInRelation()
                    ->when(!is_null($enrollStart) && !is_null($enrollEnd), function($q) use($enrollStart, $enrollEnd){
                        $q->dateFilter($enrollStart, $enrollEnd);
                    });
                })
                ->whereHas("enrolledCourses.payments", function($q){
                        $q->active();
                })
                ->active();

        if($leadStart && $leadEnd){
            $dashboardRaw->whereHas("leads", function($q) use($leadStart, $leadEnd){
                $q->dateFilter("created_at", $leadStart, $leadEnd);
            });
        }

        // dd($dashboardRaw);
        $dashboardRaw = $dashboardRaw->get();

        // dd(
        // $dashboardRaw[0]->enrolledCourses
        // ->flatMap
        // ->payments
        // ->first()
        // ->selectRaw("CASE WHEN type = 'refunded' THEN -paid_amount ELSE paid_amount END")
        // );
          
        // dd($courses[0]->leads->count());

        // dd($dashboardRaw);
        // Format for dashboard
        $dashboardData = $dashboardRaw->map(fn($row) => [
            'course_name' => $row->name,
            'course_id'   => $row->course_id,
            'students'    => (int) $row->enrolledCourses()?->count() ?? 0,
            'revenue'     => (float) $row->enrolledCourses
                                ?->flatMap?->payments
                                ?->reduce(function ($carry, $payment) use($paymentStart, $paymentEnd) {
                                    $pay = $payment->selectRaw("CASE WHEN type = 'refunded' THEN -paid_amount ELSE paid_amount END as payment")
                                        ->when(!is_null($paymentStart) && !is_null($paymentEnd), function($q) use($paymentStart, $paymentEnd){
                                            $q->dateFilter($paymentStart, $paymentEnd);
                                        });
                                    ;
                                    if(!is_null($pay)){
                                        return $carry + $pay->value("payment");
                                    }
                                    return $carry;
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
