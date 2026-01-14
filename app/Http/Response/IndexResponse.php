<?php

namespace App\Http\Response;

use App\Classes\CacheKeys;
use App\Classes\CourseCache;
use App\Classes\FaqCache;
use App\Classes\PostCache;
use App\Classes\ResponseKeys;
use App\Http\Contracts\IndexContracts;
use App\Models\Faq;
use App\Models\RatingModal;
use App\Models\Setting;
use Exception;
use Illuminate\Support\Facades\Cache;
use App\Models\Student;
use App\Models\Course;
use App\Models\Student as CRMStudent;
use App\Models\EnrolledCourse;
use App\Models\EnrolledCoursePayment;
use Carbon\Carbon;

class IndexResponse implements IndexContracts
{
    public function toResponse($request)
    {
        try {

            $settings = Setting::first();
            // $RatingModal = RatingModal::class;
            // $title = __('lms::messages.site_title');
            // $desc = __('description.home');
            // $cs =  Cache::has(CacheKeys::CATEGORIES) ? Cache::get(CacheKeys::CATEGORIES) : CacheKeys::setcourseCategories();
            // $post = Cache::has(PostCache::FIRST_POST) ? Cache::get(PostCache::FIRST_POST) : PostCache::setFristPost();
            // $faq = Cache::has(FaqCache::FAQS) ? Cache::get(FaqCache::FAQS) : FaqCache::setFaqs();
            // $courses = Cache::has(CourseCache::COURSES) ? Cache::get(CourseCache::COURSES) : CourseCache::setCourses();

                /* ---------- Students This Month ---------- */
                $studentsThisMonth = CRMStudent::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();

                /* ---------- Students Yearly ---------- */
                $studentsYearly = CRMStudent::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
                    ->whereYear('created_at', now()->year)
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get();

                /* ---------- Payments This Month ---------- */
                $paymentsThisMonth = EnrolledCoursePayment::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->where("is_deleted", 0)
                    ->sum('paid_amount');

                /* ---------- Annual Payments ---------- */
                $annualPayments = EnrolledCoursePayment::selectRaw('MONTH(created_at) as month, SUM(paid_amount) as total')
                    ->whereYear('created_at', now()->year)
                    ->where("is_deleted", 0)
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get();

                /* ---------- Paid vs Pending ---------- */
                $totalFee  = EnrolledCourse::sum('total_fee');
                $totalPaid = EnrolledCoursePayment::where("is_deleted", 0)->sum('paid_amount');
                $pending   = max($totalFee - $totalPaid, 0);

            $totalStudents = Student::count();
            $activeStudents = Student::where('is_deleted', 0)->count();

            $activeEnrolledStudents = Student::where('is_deleted', 0)
                ->whereHas('enrolledCourses')
                ->count();

            // Courses
            $totalCourses = Course::count();
            $activeCourses = Course::where('is_deleted', 0)->count();

            $activeCourses = Course::whereHas('enrolledCourses')->count();

            $totalUnpaid = EnrolledCourse::with('payments', 'student')
                ->whereHas('student', fn($q) => $q->where('is_deleted', 0)) // only active students
                ->get()
                ->sum(function ($course) {
                    $totalPaid = $course->payments()->where('is_deleted', 0)->sum('paid_amount');
                    return max($course->total_fee - $totalPaid, 0); // only positive unpaid
                });

$totalOverdue = EnrolledCourse::with('payments', 'student')
    ->whereHas('student', fn($q) => $q->where('is_deleted', 0)) // only active students
    ->where('due_date', '<', now()) // past due
    ->get()
    ->sum(function ($course) {
        $totalPaid = $course->payments()->where('is_deleted', 0)->sum('paid_amount');
        return max($course->total_fee - $totalPaid, 0);
    });

            $cert_count = EnrolledCourse::with([
                'student',
                'course',
                'certificate',
            ])
                ->whereHas("certificate")
                ->whereHas("student", function($query){
                    $query->where("is_deleted", 0);
                })
                ->orderby('created_at', 'desc')
                ->count();


                // dd($cert_count);
            // Users
            // $totalUsers = User::count();

            // $activeUsers = User::where('is_active', 1)->count(); // or last_login_at != null
            // dd('here');
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
                        'totalPaid',
                        'totalOverdue',
                        'totalUnpaid',
                        'cert_count',
                        'pending'
                    )
                );

        } catch (Exception $e) {
            dd($e->getMessage());
            return server_logs([true, $e], [true, $request]);
        }
    }
}
