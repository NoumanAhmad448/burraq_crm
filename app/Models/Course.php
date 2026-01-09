<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'crm_courses';

    protected $fillable = [
        'name',
        'description',
        'fee',
    ];

    public function enrolledCourses()
    {
        return $this->hasMany(EnrolledCourse::class);
    }
}
