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
}
