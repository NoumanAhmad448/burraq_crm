<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmStudentLog extends Model
{
    protected $table = 'crm_student_logs';

    public $timestamps = false;

    protected $fillable = [
        'crm_student_id',
        'user_id',
        'action',
        'student_snapshot',
        'logged_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $casts = [
        'student_snapshot' => 'array',
    ];
}
