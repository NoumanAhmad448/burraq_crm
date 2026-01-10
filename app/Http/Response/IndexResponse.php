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
use App\Models\User;
use App\Models\EnrolledCourse;

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

            $totalStudents = Student::count();
            $activeStudents = Student::where('is_deleted', 0)->count();

            $activeEnrolledStudents = Student::where('is_deleted', 0)
                ->whereHas('enrolledCourses')
                ->count();

            // Courses
            $totalCourses = Course::count();
            $activeCourses = Course::where('is_deleted', 0)->count();

            $activeCourses = Course::whereHas('enrolledCourses')->count();

            // Users
            // $totalUsers = User::count();

            // $activeUsers = User::where('is_active', 1)->count(); // or last_login_at != null

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
                    )
                );

        } catch (Exception $e) {
            dd($e->getMessage());
            return server_logs([true, $e], [true, $request]);
        }
    }
}
