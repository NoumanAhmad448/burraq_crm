<?php

namespace App\Classes\Inquiry;

use App\Models\Inquiry;
use Illuminate\Support\Facades\Cache;


class InquiryFollowUp
{
    public static function get($ttl = 1)
    {
        return Cache::remember('inquiry_follow_up', $ttl, function () {
            return Inquiry::where('status', 'follow_up')->count();
        });
    }
}
