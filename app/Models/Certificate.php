<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $table = 'crm_certificates';

    protected $fillable = [
        'student_id',
        'enrolled_course_id',
        'generated_by',
        'generated_count',
        'last_generated_at'
    ];

    public function enrolledCourse()
    {
        return $this->belongsTo(EnrolledCourse::class, 'enrolled_course_id');
    }
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}

