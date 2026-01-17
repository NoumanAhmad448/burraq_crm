<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnrolledCoursePayment extends Model
{
    protected $table = 'crm_course_payments';

    protected $fillable = [
        'enrolled_course_id',
        'paid_amount',
        'payment_by',
        'paid_at',
        'payment_slip_path',
        'is_deleted',
    ];

    public function enrolledCourse()
    {
        return $this->belongsTo(EnrolledCourse::class)->where("is_deleted", 0);
    }

    public function paidBy()
    {
        return $this->belongsTo(User::class, 'payment_by');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    public function logs()
    {
        return $this->hasMany(
            EnrolledCoursePaymentLog::class,
            'enrolled_course_payment_id'
        );
    }
}
