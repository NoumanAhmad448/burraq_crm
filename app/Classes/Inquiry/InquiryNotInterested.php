<?php

namespace App\Classes\Inquiry;

use App\Models\Inquiry;
use Illuminate\Support\Facades\Cache;

class InquiryNotInterested
{
    public static function get($ttl = 1)
    {
        return Cache::remember('inquiry_not_interested', $ttl, function () {
            return Inquiry::where('status', 'not_interested')->count();
        });
    }
}
