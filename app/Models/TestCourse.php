<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestCourse extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function studentCourses()
    {
        return $this->hasMany(\App\Models\TestStudentCourse::class, 'test_course_id');
    }
}
