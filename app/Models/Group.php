<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'group_name',
        'timing',
    ];

    public function courseProgress()
    {
        return $this->hasMany(GroupCourseProgress::class);
    }

    public function groupEnrollment(){
        return $this->hasMany(GroupEnrollment::class, "group_id", "id")->whereNotSoftDeleted();
    }

    public function enrolledCourses()
    {
        return $this->belongsToMany(
            EnrolledCourse::class,        // related model
            'group_enrollments',          // pivot table
            'group_id',                   // foreign key on pivot table for this model
            'crm_enrolled_course_id'      // foreign key on pivot table for related model
        )->withTimestamps()
             ->wherePivotNull('group_enrollments.deleted_at')
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

    public function getStatusAttribute()
    {
        $total = $this->modules()->count();

        if ($total === 0) {
            return 0;
        }

        $completed = $this->modules()
            ->where('progress_pct', 1)
            ->count();

        return round(($completed / $total) * 100) . "%";
    }
}
