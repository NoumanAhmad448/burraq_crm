<?php

namespace App\Http\Response;

use App\Classes\CacheKeys;
use App\Classes\CourseCache;
use App\Classes\FaqCache;
use App\Classes\LyskillsCarbon;
use App\Classes\PostCache;
use Illuminate\Support\Facades\DB;
use App\Http\Contracts\IndexContracts;
use App\Models\Certificate;
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

                $month = $request->get("month");
                $year = $request->get("year");

                if(!$month){
                    $month = now()->month;
                }else{
                    $month = LyskillsCarbon::create()->month($month)->month;
                }
                if(!$year){
                    $year = now()->year;
                }else{
                    $year = LyskillsCarbon::create()->year($year)->year;
                }


                $startOfMonth = Carbon::create($year, $month, 1)->startOfDay();
                $endOfMonth   = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();

                // dd($startOfMonth->format("Y-m-d"));

                /* ---------- Students This Month ---------- */
                $studentsThisMonth = CRMStudent::whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
                    ->where("is_deleted", 0)
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();

                /* ---------- Students Yearly ---------- */
                $studentsYearly = CRMStudent::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
                    ->whereYear('created_at', $year)
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get();

                /* ---------- Payments This Month ---------- */

                $dueThisMonth = EnrolledCourse::with(['payments' => function ($q) use ($month, $year) {
                        $q->whereMonth('created_at', $month)
                        ->whereYear('created_at', $year)
                        ->where('is_deleted', 0);
                    }])
                    ->where('is_deleted', 0)
                    ->whereHas('student', function ($q) {
                        $q->where('is_deleted', 0);
                    })
                    // Only courses with due_date in current month
                    ->whereNotNull('due_date')
                    ->whereBetween('due_date', [$startOfMonth, $endOfMonth])
                    ->get()
                    ->sum(function ($course) {
                        $totalPaid = $course->payments->sum('paid_amount');
                        $totalFee  = $course->total_fee;

                        // Only count if paid < total
                        return $totalPaid < $totalFee ? $totalFee - $totalPaid : 0;
                    });



                /* ---------- Annual Payments ---------- */
                $annualPayments = EnrolledCoursePayment::selectRaw('MONTH(created_at) as month, SUM(paid_amount) as total')
                    ->whereYear('created_at', $year)
                    ->where("is_deleted", 0)
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get();

                /* ---------- Paid vs Pending ---------- */
                $totalFee  = EnrolledCourse::where("is_deleted", 0)
                 ->whereHas("student", function($query){
                        $query->where("is_deleted",0);
                    })
                ->sum('total_fee');


                 $paymentsThisMonth = EnrolledCoursePayment::query()->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                                ->where('is_deleted', 0) // payment active
                ->whereHas('enrolledCourse', function ($q) {
                    $q->where('is_deleted', 0); // course active
                })
                ->whereHas('enrolledCourse.student', function ($q) {
                    $q->where('is_deleted', 0); // student active
                })
                ->sum('paid_amount');

                $totalPaid_g = EnrolledCoursePayment::query()->where('is_deleted', 0) // payment active
                ->whereHas('enrolledCourse', function ($q) {
                    $q->where('is_deleted', 0); // course active
                })
                ->whereHas('enrolledCourse.student', function ($q) {
                    $q->where('is_deleted', 0); // student active
                })
                ->sum('paid_amount');

                $totalPaid = EnrolledCourse::query()
                    ->where('is_deleted', 0) // course active
                    ->whereHas('student', fn ($q) => $q->where('is_deleted', 0))
                    ->withSum(['payments as total_paid' => function ($q) {
                        $q->where('is_deleted', 0);
                    }], 'paid_amount')
                    ->get()
                    ->filter(fn ($course) => $course->total_paid >= $course->total_fee)
                    ->sum('total_paid');

                    $totalPaid_m = EnrolledCourse::query()
                    ->where('is_deleted', 0) // course active
                    ->whereHas('student', fn ($q) => $q->where('is_deleted', 0))
                    ->withSum(['payments as total_paid' => function ($q) use($startOfMonth,$endOfMonth){
                        $q->where('is_deleted', 0)->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
                    }], 'paid_amount')
                    ->get()
                    ->filter(fn ($course) => $course->total_paid >= $course->total_fee)
                    ->sum('total_paid');

                $pending   = max($totalFee - $totalPaid_g, 0);

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
                ->where("is_deleted", 0)
                ->withSum(['payments as total_paid' => function ($q) {
                        $q->where('is_deleted', 0);
                    }], 'paid_amount')
                ->get()
                ->sum(function ($course) {
                    return $course->total_paid < $course->total_fee ? $course->total_fee - $course->total_paid  : 0; // only positive unpaid
                });
            $pendingThisMonth = EnrolledCourse::with('payments', 'student')
                ->whereHas('student', fn($q) => $q->where('is_deleted', 0)) // only active students
                ->where("is_deleted", 0)
                ->whereHas("payments", function($q)  use($startOfMonth, $endOfMonth){
                    $q->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
                })
                ->withSum(['payments as total_paid' => function ($q){
                        $q->where('is_deleted', 0);
                    }], 'paid_amount')
                ->get()
                ->sum(function ($course) {
                    return $course->total_paid < $course->total_fee ? $course->total_fee - $course->total_paid  : 0; // only positive unpaid
                });

            $totalOverdue = EnrolledCourse::
                whereHas('student', fn($q) => $q->where('is_deleted', 0)) // only active students
                ->whereNotNull('due_date') // past due
                ->where('due_date', '<', now()) // past due
                ->where('is_deleted', 0)
                ->withSum(['payments as total_paid' => function ($q) {
                        $q->where('is_deleted', 0);
                    }], 'paid_amount')
                ->get()
                ->sum(function ($course) {
                    return $course->total_paid < $course->total_fee ? $course->total_fee - $course->total_paid  : 0; // only positive unpaid
                });
            $dueThisMonth = EnrolledCourse::
                whereHas('student', fn($q) => $q->where('is_deleted', 0)) // only active students
                ->whereNotNull('due_date') // past due
                ->where('due_date', '<', now()) // past due
                ->where('is_deleted', 0)
                ->whereHas("payments", function($q)  use($startOfMonth, $endOfMonth){
                    $q->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
                })
                ->withSum(['payments as total_paid' => function ($q){
                        $q->where('is_deleted', 0);
                    }], 'paid_amount')
                ->get()
                ->sum(function ($course) {
                    return $course->total_paid < $course->total_fee ? $course->total_fee - $course->total_paid  : 0; // only positive unpaid
                });

        $enrolledCourses = EnrolledCourse::withSum(['payments as total_paid' => function ($q) {
                $q->where('is_deleted', 0);
            }], 'paid_amount')
            ->whereHas('certificate')
            ->whereHas('student', fn ($q) => $q->where('is_deleted', 0))
            ->get()
            ->filter(fn ($ec) => ($ec->total_paid ?? 0) >= $ec->total_fee);

        $cert_count = $enrolledCourses->count();


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
                    )
                );

        } catch (Exception $e) {
            // dd($e->getMessage());
            return server_logs([true, $e], [true, $request]);
        }
    }
}
