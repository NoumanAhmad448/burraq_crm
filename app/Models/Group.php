<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_name'
    ];

    public function progresses()
    {
        return $this->hasMany(GroupCourseProgress::class);
    }

    public function enrolledCourses()
    {
        return $this->hasMany(EnrolledCourse::class, 'group_id');
    }
}
