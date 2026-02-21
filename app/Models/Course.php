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
        return $this->hasMany(EnrolledCourse::class, "course_id", "id")->where("is_deleted", "<>", 1);
    }
    public function leads()
    {
        return $this->hasMany(Inquiry::class, "course_id")->whereNull("deleted_at");
    }

    public static function latestCourse(){
        return self::latest()->get();
    }

    public function scopeActive($query){
        return $query->where("is_deleted", "<>", 1);
    }
}
