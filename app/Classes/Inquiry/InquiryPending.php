<?php

namespace App\Classes\Inquiry;

use App\Models\Inquiry;
use Illuminate\Support\Facades\Cache;

class InquiryPending
{
    public static function get($ttl = 1)
    {
        return Cache::remember('inquiry_pending', $ttl, function () {
            return Inquiry::where('status', 'pending')->count();
        });
    }
}
