<?php

namespace App\Classes\Inquiry;

use App\Models\Inquiry;
use Illuminate\Support\Facades\Cache;


class InquiryContacted
{
    public static function get($ttl = 1)
    {
        return Cache::remember('inquiry_contacted', $ttl, function () {
            return Inquiry::where('status', 'contacted')->count();
        });
    }
}
