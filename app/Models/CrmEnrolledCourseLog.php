<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmEnrolledCourseLog extends Model
{
    protected $table = 'crm_enrolled_course_logs';

    public $timestamps = false;

    protected $fillable = [
        'student_id',
        'course_id',
        'enrolled_course_id',
        'user_id',
        'action',
        'total_fee',
        'snapshot',
        'logged_at',
    ];

    protected $casts = [
        'snapshot' => 'array',
    ];
}
