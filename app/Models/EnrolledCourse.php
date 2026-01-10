<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnrolledCourse extends Model
{
    protected $table = 'crm_enrolled_courses';

    protected $fillable = [
        'student_id',
        'course_id',
        'total_fee',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function payments()
    {
        return $this->hasMany(EnrolledCoursePayment::class, 'enrolled_course_id');
    }

    public function totalPaid()
    {
        return $this->payments()->sum('amount');
    }

    public function remainingAmount()
    {
        return $this->course->fee - $this->totalPaid();
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }



    // public function payments()
    // {
    //     return $this->hasMany(Payment::class)
    //                 ->where('is_deleted', 0);
    // }

}
