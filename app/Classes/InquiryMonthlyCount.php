<?php

namespace App\Classes;

use App\Models\Inquiry;
use Illuminate\Support\Facades\Cache;

class InquiryMonthlyCount
{
    public static function get(int $year)
    {
        $cacheKey = "inquiries_monthly_count_{$year}";

        return Cache::remember($cacheKey, 60, function () use ($year) {
            $data = Inquiry::query()
                ->whereYear('created_at', $year)
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month')
                ->toArray();

            // Ensure all 12 months exist
            $months = [];
            for ($m = 1; $m <= 12; $m++) {
                $months[$m] = $data[$m] ?? 0;
            }

            return $months;
        });
    }
}
