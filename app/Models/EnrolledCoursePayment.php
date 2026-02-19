<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnrolledCoursePayment extends Model
{
    protected $table = 'crm_course_payments';
    public const REFUNDED = "refunded";
    protected $fillable = [
        'enrolled_course_id',
        'paid_amount',
        'payment_by',
        'paid_at',
        'payment_slip_path',
        'is_deleted',
        'payment_method',
        'payment_date',
        'type',
    ];

    public function enrolledCourse()
    {
        return $this->belongsTo(EnrolledCourse::class)->where("is_deleted", 0);
    }

    public function paidBy()
    {
        return $this->belongsTo(User::class, 'payment_by');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    public function logs()
    {
        return $this->hasMany(
            EnrolledCoursePaymentLog::class,
            'enrolled_course_payment_id'
        );
    }

    public function scopeenrolledCourseInRelation($query)
    {
        return $query->whereHas('enrolledCourse', function ($q) {
            $q->activeStatus();
        });
    }

    public function scopeRefundedPayments($course_id)
    {
        if (!empty($course_id)) {
            return $this->where("type", self::REFUNDED)->where("enrolled_course_id", $course_id)->active();
        }
        return $this->where("type", self::REFUNDED);
    }

    public function scopeNoRefundedPayments($query, $course_id=0)
    {
        if (!empty($course_id)) {
            return $query->where("type", "<>", self::REFUNDED)->where("enrolled_course_id", $course_id)->active();
        }
        return $query->whereNull("type");
    }

    public function scopeActive($query)
    {
        return $query->where("is_deleted", "<>", 1);
    }

    public function scopeNotExists($query)
    {
        return !$query->exists();
    }
    public function scopeRegDate($query, $month, $year, $date = "registration_date")
    {
        return $query->when(!is_null($month), function ($q) use ($month, $date) {
            $q->whereMonth($date, $month);
        })
            ->when(!is_null($year), function ($q) use ($year, $date) {
                $q->whereYear($date, $year);
            });
    }

    public function scopeNetAmount($query)
    {
        return $query->selectRaw("
            COALESCE(
                SUM(
                    CASE 
                        WHEN type = 'refunded' THEN -paid_amount
                        ELSE paid_amount
                    END
                ), 0
            ) AS amount
        ");
    }

    public function scopeTotalPaid($query)
    {
        return $query
            ->where('is_deleted', 0)
            ->netamount()->value("amount");
    }
}
