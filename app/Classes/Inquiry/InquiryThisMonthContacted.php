<?php

namespace App\Classes\Inquiry;

use App\Models\Inquiry;
use Illuminate\Support\Facades\Cache;

class InquiryThisMonthContacted
{
    public static function get($month, $year, $ttl = 1)
    {
        return Cache::remember("inquiry_contacted_{$month}_{$year}", $ttl, function () use ($month, $year) {
            return Inquiry::where('status', 'contacted')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->count();
        });
    }
}
