<?php

namespace App\Http\Controllers\Admin;

use App\Classes\StartEndDateFilter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\EnrolledCourse;
use App\Classes\LyskillsCarbon;

class InquiryDashboardController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->get('limit', 10); // default 10
        $coursesQuery = Course::with(['enrolledCourses.payments' => function ($q) {
            $q->where('is_deleted', '!=', 1);
        }]);

        // Apply course filter if provided
        $coursesQuery->when($request->course_id, fn($q) => $q->where('id', $request->course_id));

        $courses = $coursesQuery->get();

        $dashboardData = [];

        foreach ($courses as $course) {

            // Filter enrolled students dynamically
            $enrolled = $course->enrolledCourses;
            $enrolled = StartEndDateFilter::handle($request, $enrolled,"admission_date");

                $enrolled->where('is_deleted', '!=', 1)
                ->filter(function($enrollment) use ($request) {
                    if ($request->start_date && $request->end_date) {
                        return LyskillsCarbon::parse($enrollment->admission_date)
                            ->between(LyskillsCarbon::parse($request->start_date), LyskillsCarbon::parse($request->end_date));
                    } elseif ($request->start_date) {
                        return LyskillsCarbon::parse($enrollment->admission_date)
                            ->gte(LyskillsCarbon::parse($request->start_date));
                    } elseif ($request->end_date) {
                        return LyskillsCarbon::parse($enrollment->admission_date)
                            ->lte(LyskillsCarbon::parse($request->end_date));
                    }
                    return true;
                });
            // dd($enrolled);
            $studentsCount = $enrolled->count();

            // Calculate revenue
            $revenue = 0;
            foreach ($enrolled as $enrollment) {

                // Normal
                if (is_null($enrollment->status)) {
                    $revenue += $enrollment->payments
                        ->where('type', '!=', 'refunded')
                        ->sum('paid_amount');
                }

                // Refunded
                if ($enrollment->status === 'refunded') {
                    $normalPayments = $enrollment->payments
                        ->where('type', '!=', 'refunded')
                        ->sum('paid_amount');
                    $refundedPayments = $enrollment->payments
                        ->where('type', 'refunded')
                        ->sum('paid_amount');
                    $revenue += ($normalPayments - $refundedPayments);
                }
            }

            $leads_query = StartEndDateFilter::date($request, $course->leads());
            $leadsCount = StartEndDateFilter::handle($request, $leads_query)->whereNull('deleted_at')->count();


            $conversion = $leadsCount > 0 ? round(($studentsCount / $leadsCount) * 100, 2) : 0;

            $dashboardData[] = [
                'course_name' => $course->name,
                'students' => $studentsCount,
                'revenue' => $revenue,
                'conversion' => $conversion,
                'leads' => $leadsCount
            ];
        }

        // Limit cards display
        $displayCourses = $dashboardData;
        if (is_numeric($limit)) {
            $displayCourses = array_slice($dashboardData, 0, $limit);
        }

        return view('admin.inquiries.dashboards', compact('displayCourses', 'dashboardData', 'limit'));
    }
}
