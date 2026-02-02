<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    // use SoftDeletes;

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
        'registration_date',
        'role',
        'is_deleted',
        'payment_slip_path',
        'status',
        'drop_reason',
    ];

    public function enrolledCourses()
    {
        return $this->hasMany(EnrolledCourse::class, 'student_id')->where("is_deleted", 0);
    }
}
