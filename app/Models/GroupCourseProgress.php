<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GroupCourseProgress extends Model
{
    use HasFactory;

    protected $table = 'group_course_progress';

    protected $fillable = [
        'group_id',
        'course_id',
        'instructor_id',
        'progress_pct'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id')
            ->where('role', config('settings.roles.instructor'));
    }
}
