<?php

namespace App\Macros;

use App\Models\EnrolledCourse;
use Illuminate\Database\Eloquent\Builder;


class EnrolledCourseScope
{
    public static function register()
    {
        /*
        |--------------------------------------------------------------------------
        | canBeRefunded
        |--------------------------------------------------------------------------
        | Filters models that have at least one refunded payment
        */
        Builder::macro('canBeRefunded', function () {
            return $this->whereHas('payments', function ($query) {
                $query->where('status', \App\Models\EnrolledCourse::REFUNDED ?? 'refunded')->exists();
            });
        });

        /*
        |--------------------------------------------------------------------------
        | refundedPayment
        |--------------------------------------------------------------------------
        */
        Builder::macro('refundedPayment', function () {
            return $this->where('status', \App\Models\EnrolledCourse::REFUNDED ?? 'refunded');
        });

        /*
        |--------------------------------------------------------------------------
        | droppedCourse
        |--------------------------------------------------------------------------
        */
        Builder::macro('droppedCourse', function () {
            return $this->where('status', \App\Models\EnrolledCourse::DROPPED ?? 'dropped');
        });

        /*
        |--------------------------------------------------------------------------
        | activeStatus
        |--------------------------------------------------------------------------
        */
        Builder::macro('activeStatus', function () {
            return $this->where(function($q){
                $q->whereNull('status')->orWhere("status", EnrolledCourse::ACTIVE);
            })
            ->activeCourse();
        });

        /*
        |--------------------------------------------------------------------------
        | enrolledCourseInRelation
        |--------------------------------------------------------------------------
        */
        Builder::macro('enrolledCourseInRelation', function () {
            return $this->whereHas('enrolledCourse', function ($q) {
                $q->activeStatus(); // assumes activeStatus() macro/scope exists
            });
        });

        /*
        |--------------------------------------------------------------------------
        | refundedPayments
        |--------------------------------------------------------------------------
        */
        Builder::macro('refundedPayments', function ($course_id = null, $modelClass = null) {
            $query = $this->where('type', $modelClass ? $modelClass::REFUNDED : 'refunded');

            if (!empty($course_id)) {
                $query->where('enrolled_course_id', $course_id)
                    ->active(); // assumes active() macro exists
            }

            return $query;
        });

        /*
        |--------------------------------------------------------------------------
        | noRefundedPayments
        |--------------------------------------------------------------------------
        */
        Builder::macro('noRefundedPayments', function ($course_id = 0, $modelClass = null) {
            if (!empty($course_id)) {
                return $this->where('type', '<>', $modelClass ? $modelClass::REFUNDED : 'refunded')
                    ->where('enrolled_course_id', $course_id)
                    ->active(); // assumes active() macro exists
            }

            return $this->whereNull('type');
        });
    }
}
