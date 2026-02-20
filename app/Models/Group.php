<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_name',
        'timing',
    ];

    public function courseProgress()
    {
        return $this->hasMany(GroupCourseProgress::class);
    }

    public function enrolledCourses()
    {
        return $this->belongsToMany(
            EnrolledCourse::class,        // related model
            'group_enrollments',          // pivot table
            'group_id',                   // foreign key on pivot table for this model
            'crm_enrolled_course_id'      // foreign key on pivot table for related model
        )->withTimestamps()
            //  ->whereNull('group_enrollments.deleted_at')
            ;  // ignore soft deleted
    }
    public function instructors()
    {
        return $this->belongsToMany(User::class, 'group_instructors', 'group_id', 'instructor_id')
            //  ->whereNull('group_enrollments.deleted_at')
             ;  // ignore soft deleted
    }


    public function modules(){
        return $this->hasMany(GroupCourseProgress::class, "group_id");
    }
}
