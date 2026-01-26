<?php

namespace App\Classes\Inquiry;

use App\Models\Inquiry;
use Illuminate\Support\Facades\Cache;


class InquiryNotContacted
{
    public static function get($ttl = 1)
    {
        return Cache::remember('inquiry_not_contacted', $ttl, function () {
            return Inquiry::whereNull('status')
                ->orWhere('status', 'pending')
                ->count();
        });
    }
}
