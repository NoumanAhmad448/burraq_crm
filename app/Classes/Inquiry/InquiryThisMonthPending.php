<?php

namespace App\Classes\Inquiry;

use App\Models\Inquiry;
use Illuminate\Support\Facades\Cache;

class InquiryThisMonthPending
{
    public static function get($month, $year, $ttl = 1)
    {
        return Cache::remember("inquiry_pending_{$month}_{$year}", $ttl, function () use ($month, $year) {
            return Inquiry::where('status', 'pending')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->count();
        });
    }
}
