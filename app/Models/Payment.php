<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'crm_payments';

    protected $fillable = [
        'enrolled_course_id',
        'amount',
        'payment_date',
        'method',
        'note'
    ];

    public function enrolledCourse()
    {
        return $this->belongsTo(EnrolledCourse::class);
    }
}
