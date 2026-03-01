<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestStudentCourse extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(TestStudent::class, 'test_student_id');
    }

    public function course()
    {
        return $this->belongsTo(TestCourse::class, 'test_course_id');
    }

    public function payments()
    {
        return $this->hasMany(TestPayment::class);
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            $paid = $model->payments()->sum('amount');

            if ($paid >= $model->final_price) {
                $model->status = 'completed';
            }
        });
    }
}
