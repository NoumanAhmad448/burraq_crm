<?php

namespace App\Models;

use App\Classes\LyskillsCarbon;
use Illuminate\Database\Eloquent\Model;

class EnrolledCourse extends Model
{
    protected $table = 'crm_enrolled_courses';
    public const REFUNDED = "refunded";
    public const COMPLETED = "Completed";
    public const DROPPED = "dropped";
    public const DELETED = "deleted";

    protected $fillable = [
        'student_id',
        'course_id',
        'total_fee',
        'admission_date',
        'due_date',
        'is_deleted',
        'deleted_by',
        'deleted_at',
        'status',
        'status_note',
        'status_updated_at',
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id')
            ->instructor();
    }
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function payments()
    {
        return $this->hasMany(EnrolledCoursePayment::class, 'enrolled_course_id')->where("is_deleted", 0);
    }

    public function remainingAmount()
    {
        return $this->course->fee - $this->totalPaid();
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function activeStudent()
    {
        return $this->belongsTo(Student::class)->where("is_deleted", 0);
    }
    public function scopeactiveStudentInRelation($query)
    {
        return $query->whereHas('student', fn($q) => $q->active());
    }

    public function certificate()
    {
        return $this->hasMany(Certificate::class, 'enrolled_course_id');
    }

    // Format admission date using LyskillsCarbon
    public function getFormattedAdmissionDateAttribute()
    {
        if (!$this->admission_date) return null;
        return LyskillsCarbon::parse($this->admission_date)->format('d-m-Y');
    }

    // Format due date using LyskillsCarbon
    public function getFormattedDueDateAttribute()
    {
        if (!$this->due_date) return null;
        return LyskillsCarbon::parse($this->due_date)->format('d-m-Y');
    }

    public function scopePendingCourses($query)
    {
        return $query->whereNotNull('due_date') // past due
            ->where('due_date', '<', now()); // past due
    }

    public function scopeTotalActivePayment($query)
    {
        return $query->withSum(['payments as total_paid' => function ($q) {
            $q->active()->noRefundedPayments();
        }], 'paid_amount');
    }
    public function scopeTotalIncome($query)
    {
        return $query->whereHas('student', function ($q) {
            $q->where('is_deleted', 0);
        })->where('is_deleted', 0)->sum("total_fee");
    }
    public function scopeTotalMonthlyIncome($query, $month, $year)
    {
        return $query->whereHas('student', function ($q) {
            $q->where('is_deleted', 0);
        })->where('is_deleted', 0)->when($month, function ($query) use ($month) {
            $query->whereMonth('admission_date', $month);
        })
            ->when($year, function ($query) use ($year) {
                $query->whereYear('admission_date', $year);
            })->sum("total_fee");
    }

    public function scopeActiveCourse($query)
    {
        return $query->where('is_deleted', "<>", 1);
    }
    public function scopePaidStudentsOnly($query)
    {
        return $query->whereRaw(
            '(SELECT COALESCE(SUM(paid_amount), 0)
            FROM crm_course_payments as payments
            WHERE payments.enrolled_course_id = crm_enrolled_courses.id
            AND payments.is_deleted = 0
            ) < crm_enrolled_courses.total_fee'
        );
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

    public function scopeignoreOrAccept($query, $status)
    {
        return $query->when($status, function ($q, $status) {
            $q->where("status", empty($status) ? "<>" : "=", empty($status) ? self::COMPLETED : $status);
        });
    }

    public function scopeGetCourse($query)
    {
        if (request()->course_id) {
            return $query->where('course_id', request()->course_id);
        }
        return $query;
    }

    public function scopeCanBeRefunded()
    {
        return $this->payments()
            ->where('status', self::REFUNDED)
            ->exists();
    }
    public function scopeRefundedPayment()
    {
        return $this
            ->where('status', self::REFUNDED);
    }
    public function scopeDroppedCourse()
    {
        return $this
            ->where('status', self::DROPPED);
    }
    public function scopeActiveStatus($query)
    {
        return $query
            ->whereNull('status')
            ->activeCourse();
    }
}
