<?php

namespace App\Models;

use App\Classes\LyskillsCarbon;
use Illuminate\Database\Eloquent\Model;

class EnrolledCourse extends Model
{
    protected $table = 'crm_enrolled_courses';

    protected $fillable = [
        'student_id',
        'course_id',
        'total_fee',
        'admission_date',
        'due_date',
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

    public function certificate()
    {
        return $this->hasMany(Certificate::class, 'enrolled_course_id');
    }

     // Format admission date using LyskillsCarbon
    public function getFormattedAdmissionDateAttribute()
    {
        if (!$this->admission_date) return null;
        return LyskillsCarbon::parse($this->admission_date)->format('d-m-Y');
    }

    // Format due date using LyskillsCarbon
    public function getFormattedDueDateAttribute()
    {
        if (!$this->due_date) return null;
        return LyskillsCarbon::parse($this->due_date)->format('d-m-Y');
    }
}
