<?php

namespace App\Classes;

use App\Models\Inquiry;
use Illuminate\Support\Facades\Cache;

class InquiryDashboardResponse
{
    public static function getStatusWise()
    {
        return Cache::remember('inquiries_status_wise', 60, function () {
            $statuses = ['pending', 'contacted', 'follow_up', 'not_interested'];

            $result = [];
            foreach ($statuses as $status) {
                $result[ucfirst(str_replace('_', ' ', $status))] = Inquiry::where('status', $status)
                    ->count();
            }

            return $result;
        });
    }

    public static function getMonthlyTrend($year)
    {
        return Cache::remember("inquiries_monthly_count_{$year}", 60, function () use ($year) {
            $data = Inquiry::whereYear('created_at', $year)
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month')
                ->toArray();

            $months = [];
            for ($m = 1; $m <= 12; $m++) {
                $months[$m] = $data[$m] ?? 0;
            }

            return $months;
        });
    }
}
