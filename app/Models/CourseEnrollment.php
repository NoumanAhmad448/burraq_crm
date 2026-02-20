<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseEnrollment extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['course_id','user_id'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }


    public function course()
    {
        return $this->belongsTo(Course::class,'course_id','id');
    }

}
