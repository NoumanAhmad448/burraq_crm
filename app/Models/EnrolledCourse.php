<?php

namespace App\Models;

use App\Classes\LyskillsCarbon;
use Illuminate\Database\Eloquent\Model;

class EnrolledCourse extends Model
{
    
    protected $table = 'crm_enrolled_courses';
    public const REFUNDED = "refunded";
    public const COMPLETED = "Completed";
    public const DROPPED = "dropped";
    public const ACTIVE = "active";
    public const DELETED = "deleted";

    protected $fillable = [
        'student_id',
        'course_id',
        'total_fee',
        'admission_date',
        'due_date',
        'is_deleted',
        'deleted_by',
        'deleted_at',
        'status',
        'status_note',
        'status_updated_at',
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id')
            ->instructor();
    }
    public function inquiries(){
        return $this->hasMany(Inquiry::class, "course_id");
    }
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function payments()
    {
        return $this->hasMany(EnrolledCoursePayment::class, 'enrolled_course_id')->where("is_deleted", 0);
    }

    public function remainingAmount()
    {
        return $this->course->fee - $this->totalPaid();
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function activeStudent()
    {
        return $this->belongsTo(Student::class)->where("is_deleted", 0);
    }
    public function scopeactiveStudentInRelation($query)
    {
        return $query->whereHas('student', fn($q) => $q->active());
    }

    public function certificate()
    {
        return $this->hasMany(Certificate::class, 'enrolled_course_id');
    }

    
    public function groupEnrollment(){
        return $this->hasOne(GroupEnrollment::class, "crm_enrolled_course_id", "id");
    }
    

}
