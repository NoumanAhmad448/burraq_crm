<?php

namespace App\Classes;

use App\Classes\LyskillsCarbon;
use App\Models\Inquiry;

class InqStatus
{
    public static function handle()
    {
        $type = request('type', 'all');


        $query = Inquiry::latest();

        switch ($type) {
            case 'pending':
                $query->where('status', 'pending');
                break;

            case 'contacted':
                $query->where('status', 'contacted');
                break;

            case 'follow_up':
                $query->where('status', 'follow_up');
                break;

            case 'not_interested':
                $query->where('status', 'not_interested');
                break;

            case 'not_contacted':
                $query->whereNull('status');
                break;

            // month-based (later refinement)
            case 'this_month_pending':
                $query->where('status', 'pending')
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
                break;

            case 'this_month_contacted':
                $query->where('status', 'contacted')
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
                break;

            case 'all':
            default:
                // no filter
                break;
        }
        return $query;
    }
}
