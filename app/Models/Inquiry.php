<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inquiry extends Model
{
    use SoftDeletes;

    protected $table = 'crm_inquiries';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'note',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
        'course_id',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function scopePending($q)
    {
        return $q->where('status', 'pending');
    }

    public function scopeContacted($q)
    {
        return $q->where('status', 'contacted');
    }

    public function scopeFollowUp($q)
    {
        return $q->where('status', 'follow_up');
    }

    public function scopeNotInterested($q)
    {
        return $q->where('status', 'not_interested');
    }

    public function scopeNotContacted($q)
    {
        return $q->whereNull('status')->orWhere('status', 'pending');
    }

    public function scopeThisMonthPending($q)
    {
        return $q->pending()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
    }

    public function scopeThisMonthContacted($q)
    {
        return $q->contacted()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
    }
}
