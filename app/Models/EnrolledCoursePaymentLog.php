<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnrolledCoursePaymentLog extends Model
{
    protected $table = 'crm_enrolled_course_payment_logs';

    protected $fillable = [
        'enrolled_course_payment_id',
        'action',
        'old_data',
        'new_data',
        'performed_by',
        'performed_by_name',
        'performed_by_email',
        'performed_by_role',
        'ip_address',
        'user_agent',
    ];
}
