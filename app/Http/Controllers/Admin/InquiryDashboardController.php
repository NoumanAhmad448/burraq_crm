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

        $dashboardRaw = DB::table('crm_courses as c')
            ->leftJoin('crm_enrolled_courses as ec', function ($join) use ($enrollStart, $enrollEnd) {
                $join->on('ec.course_id', '=', 'c.id')
                    ->where('ec.is_deleted', '!=', 1);

                if ($enrollStart && $enrollEnd) {
                    $join->whereBetween('ec.admission_date', [$enrollStart, $enrollEnd]);
                }
            })
            ->leftJoin('crm_students as s', function ($join) {
                $join->on('s.id', '=', 'ec.student_id')
                    ->where('s.is_deleted', '!=', 1);
            })
            ->leftJoin('crm_course_payments as p', function ($join) use ($paymentStart, $paymentEnd) {
                $join->on('p.enrolled_course_id', '=', 'ec.id')
                    ->where('p.is_deleted', '!=', 1);

                if ($paymentStart && $paymentEnd) {
                    $join->whereBetween('p.payment_date', [$paymentStart, $paymentEnd]);
                }
            })
            ->leftJoin('crm_inquiries as l', function ($join) use ($leadStart, $leadEnd) {
                $join->on('l.course_id', '=', 'c.id')
                    ->whereNull('l.deleted_at');

                if ($leadStart && $leadEnd) {
                    $join->whereBetween('l.created_at', [$leadStart, $leadEnd]);
                }
            })
            ->when($courseId, fn($q) => $q->where('c.id', $courseId))
            ->where('c.is_deleted', '!=', 1)
            ->groupBy('c.id', 'c.name')
            ->selectRaw("
            c.id as course_id,
            c.name as course_name,
            COUNT(DISTINCT s.id) as students,
            COALESCE(
                    SUM(
                        p.paid_amount *
                        (CASE WHEN p.type = 'refunded' THEN -1 ELSE 1 END)
                    ), 0
                )
            AS revenue,
            COUNT(DISTINCT l.id) as leads,
            CASE 
                WHEN COUNT(DISTINCT l.id) > 0
                THEN ROUND((COUNT(DISTINCT s.id) / COUNT(DISTINCT l.id)) * 100, 2)
                ELSE 0
            END as conversion
        ")
        ->get();

            // Format for dashboard
            $dashboardData = $dashboardRaw->map(fn($row) => [
                'course_name' => $row->course_name,
                'course_id'   => $row->course_id,
                'students'    => (int) $row->students,
                'revenue'     => (float) $row->revenue,
                'conversion'  => (float) $row->conversion,
                'leads'       => (int) $row->leads
            ])->toArray();


        // Limit cards display
        $displayCourses = $dashboardData;

        return view('admin.inquiries.dashboards', compact('displayCourses', 'dashboardData'));
    }
}
