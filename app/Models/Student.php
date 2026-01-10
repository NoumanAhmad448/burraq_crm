<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'crm_students';

    protected $fillable = [
        'name',
        'father_name',
        'cnic',
        'mobile',
        'email',
        'photo',
        'admission_date',
        'due_date',
        'total_fee',
        'paid_fee',
        'remaining_fee',
        'role',
        'is_deleted',
        'payment_slip_path',
    ];

    public function enrolledCourses()
    {
        return $this->hasMany(EnrolledCourse::class, 'student_id');
    }
}
