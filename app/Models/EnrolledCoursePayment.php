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
    ];

    public function enrolledCourse()
    {
        return $this->belongsTo(EnrolledCourse::class);
    }

    public function paidBy()
    {
        return $this->belongsTo(User::class, 'payment_by');
    }
}
