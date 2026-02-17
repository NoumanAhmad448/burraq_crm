<?php

namespace App\Classes;

use App\Classes\LyskillsCarbon;
use Carbon\Carbon;

class StartEndDateFilter
{
    public static function handle($request, $query, $created_at="created_at")
    {
        $month       = $request->month;
        $year        = $request->year;
        $lastMonths  = $request->last_months;
        // dd($lastMonths);
        // Case 3: Month + Year Filter
        if ($month && $year) {

            $start = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $end   = Carbon::createFromDate($year, $month, 1)->endOfMonth();

            $query->whereBetween($created_at, [
                $start->startOfDay(),
                $end->endOfDay()
            ]);
            // dd($query);
        }
        /*
        |--------------------------------------------------------------------------
        | 4. Last N Months (Rolling)
        |--------------------------------------------------------------------------
        */ elseif ($lastMonths) {
            $end   = LyskillsCarbon::now()->endOfDay();
            $start = LyskillsCarbon::now()
                ->subMonths($lastMonths)
                ->startOfDay();

            $query->whereBetween($created_at, [$start, $end]);
            // dd($query);
        }
        return $query;
    }

    public static function date($request, $query, $created_at="created_at"){
        $startDate = $request->start_date;
        $endDate   = $request->end_date;
    

        // Case 1: Only End Date
        if (!$startDate && $endDate) {
            $query->whereDate($created_at, $endDate);
        }

        // Case 2: Start and End Date (Inclusive)
        if ($startDate && $endDate) {
            $query->whereBetween($created_at, [
                LyskillsCarbon::parse($startDate)->startOfDay(),
                LyskillsCarbon::parse($endDate)->endOfDay()
            ]);
        }
        return $query;
    }
}
