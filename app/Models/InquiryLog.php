<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InquiryLog extends Model
{
    protected $table = 'crm_inquiry_logs';

    protected $fillable = [
        'inquiry_id',
        'action',
        'old_data',
        'new_data',
        'action_by',
        'ip_address',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'action_by');
    }
}
