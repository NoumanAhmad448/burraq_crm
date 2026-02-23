<?php

namespace App\Classes;

use App\Models\EnrolledCourse;
use App\Models\GroupEnrollment;
use Illuminate\Support\Facades\Cache;

class StudentEnrolledCourseCache
{
    /**
     * Get enrolled courses filtered by student registration month/year (cached)
     *
     * @param int|null $month
     * @param int|null $year
     * @param int $ttlSeconds
     */
    public static function get(?int $month = null, ?int $year = null, int $ttlSeconds = 1, $status = "")
    {
        // dd("here");
        $cacheKey = self::cacheKey($month, $year);

        return Cache::remember($cacheKey, $ttlSeconds, function () use ($month, $year, $status) {

            $query = EnrolledCourseDuePaymentCache::commonLogic($month, $year, $status, "payment_date")
                ->latest();
                // \printQuery($query);
                return $query->get();
        });
    }

    /**
     * Cache key generator
     */
    protected static function cacheKey(?int $month, ?int $year): string
    {
        return 'student_enrolled_courses_'
            . ($month ?? 'all') . '_'
            . ($year ?? 'all');
    }

    public static function group($month, $year, $status, $date)
    {
        $query = GroupEnrollment::query()->with("enrolledCourse");

        $query->where("group_id", request()->group_id);
        // \printQuery($query);
        $enrollment_ids = $query->pluck("crm_enrolled_course_id")->toArray();
        // dd($results);

        $query = EnrolledCourse::query();

        if(!is_null($month) || !is_null($year)){
            $query->whereHas('payments', function ($q) use ($month, $year, $date) {
                $q->regDate($month, $year, $date)
                // ->active()
                ;
            });
        }
        if (EnrolledCourse::DROPPED == $status) {
            $query->droppedCourse();
            $query->whereHas('student', function ($q) {
                $q->active()
                ;
            });
        }
        if(!empty($status)){
        $query->whereHas('student', function ($q) use ($status) {
                $q->ignoreOrAccept($status);
                $q->active();
            }
        );
        }
        
        if(request()->course_id){
            $query->whereHas('enrolledCourse', function ($q){
                $q->getCourse();
            });
        }
        

        $query->getCourse();
        $query->getGroup();
        $query = $query->whereIn("id", $enrollment_ids ?? [])->get();
        return $query;
    }
}
