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
        'is_deleted',
    ];

    public function enrolledCourses()
    {
        return $this->hasMany(EnrolledCourse::class);
    }
    public function leads()
    {
        return $this->hasMany(Inquiry::class, "course_id");
    }

    public static function latestCourse(){
        return self::latest()->get();
    }
}
