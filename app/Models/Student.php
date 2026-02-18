<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    // use SoftDeletes;

    protected $table = 'crm_students';
    public const COMPLETED = "completed";

    protected $fillable = [
        'name',
        'father_name',
        'cnic',
        'mobile',
        'email',
        'photo',
        'admission_date',
        'due_date',
        'total_fee',
        'paid_fee',
        'remaining_fee',
        'registration_date',
        'role',
        'is_deleted',
        'payment_slip_path',
        'status',
        'drop_reason',
    ];

    public function enrolledCourses()
    {
        return $this->hasMany(EnrolledCourse::class, 'student_id')->where("is_deleted", 0);
    }

    public function scopeRegDate($q, $month, $year, $date = "registration_date")
    {
        return $q->when(!is_null($month), function ($q) use ($month, $date) {
            $q->whereMonth($date, $month);
        })
            ->when(!is_null($year), function ($q) use ($year, $date) {
                $q->whereYear($date, $year);
            });
    }

    public function scopeIgnoreOrAccept($query, $status)
    {   
        if (empty($status)) {
            return $query->where('status', '<>', self::COMPLETED);
        }

        return $query->where('status', $status);
    }

    public function scopeActive($query){
        return $query->where('is_deleted', "<>" , 1)->orWhereNull('is_deleted');
    }

    public function scopeInactive($query){
        return $query->where('is_deleted', 1);
    }
}
