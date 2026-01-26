<?php

namespace App\Classes\Inquiry;

use App\Models\Inquiry;
use Illuminate\Support\Facades\Cache;

class InquiryTotal
{
    public static function get($ttl = 1)
    {
        return Cache::remember('inquiry_total', $ttl, function () {
            return Inquiry::count();
        });
    }
}
