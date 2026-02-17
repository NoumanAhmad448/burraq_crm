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
        $coursesQuery = Course::with([
            'enrolledCourses' => function ($q) use ($request) {
                $q->where('is_deleted', '!=', 1);

                $q = StartEndDateFilter::handle($request, $q, 'admission_date');
                $q = StartEndDateFilter::date($request, $q, 'admission_date');

                $q->with(['payments' => function ($q2) {
                    $q2->where('is_deleted', '!=', 1);
                }]);
            },
            'leads' => function ($q) use ($request) {
                $q->whereNull('deleted_at');

                $q = StartEndDateFilter::handle($request, $q, 'created_at');
                $q = StartEndDateFilter::date($request, $q, 'created_at');
            }
        ]);

        // Apply course filter if provided
        $coursesQuery->when($request->course_id, fn($q) => $q->where('id', $request->course_id));

        // dd($coursesQuery->toSql());
        $courses = $coursesQuery->where('is_deleted', '!=', 1)->get();
        // dd($courses->count());
        $dashboardData = [];

        foreach ($courses as $course) {

           $enrolledQuery = $course->enrolledCourses;
            $studentsCount = $enrolledQuery->count();

            // Calculate revenue
            $revenue = 0;
            foreach ($enrolledQuery as $enrollment) {
                $normalPayments = $enrollment->payments
                    ->where('type', '!=', 'refunded')
                    ->sum('paid_amount');
                $refundedPayments = $enrollment->payments
                    ->where('type', 'refunded')
                    ->sum('paid_amount');

                if (is_null($enrollment->status)) {
                    $revenue += $normalPayments;
                } elseif ($enrollment->status === 'refunded') {
                    $revenue += ($normalPayments - $refundedPayments);
                }
            }

            $leadsQuery = $course->leads;
            $leadsCount = $leadsQuery->count();

            $conversion = $leadsCount > 0 ? round(($studentsCount / $leadsCount) * 100, 2) : 0;

            $dashboardData[] = [
                'course_name' => $course->name,
                'course_id' => $course->id,
                'students' => $studentsCount,
                'revenue' => $revenue,
                'conversion' => $conversion,
                'leads' => $leadsCount
            ];
        }

        // Limit cards display
        $displayCourses = $dashboardData;

        return view('admin.inquiries.dashboards', compact('displayCourses', 'dashboardData'));
    }
}
