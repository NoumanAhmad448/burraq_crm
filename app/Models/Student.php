<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

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

    public function scopeRegDate($query, $month, $year, $date = "registration_date")
    {
        if (!is_null($month) && !is_null($year)) {

            $start = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $end   = Carbon::createFromDate($year, $month, 1)->endOfMonth();

            return $query->whereBetween($date, [
                $start->startOfDay(),
                $end->endOfDay()
            ]);
        }
    }

    public function scopeActive($query)
    {
        return $query->where('is_deleted', 0)->orWhereNull('is_deleted');
    }

    public function scopeInactive($query)
    {
        return $query->where('is_deleted', 1);
    }
}
