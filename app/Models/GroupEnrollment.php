<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupEnrollment extends Model
{
    use HasFactory, SoftDeletes;


    protected $table = 'group_enrollments';

    protected $fillable = [
        'group_id',
        'crm_enrolled_course_id',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function enrolledCourse()
    {
        return $this->belongsTo(EnrolledCourse::class, 'crm_enrolled_course_id');
    }
}