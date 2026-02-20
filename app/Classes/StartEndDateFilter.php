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
        $start = null;
        $end = null;
        // dd($lastMonths);
        // Case 3: Month + Year Filter
        if ($month && $year) {

            $start = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $end   = Carbon::createFromDate($year, $month, 1)->endOfMonth();
            // dd($query);
        }

        elseif ($year) {
            $date = Carbon::createFromDate($year, 1, 1);
            $start = $date->copy()->startOfYear();
            $end   = $date->copy()->endOfYear();
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
         }

         if(empty($start) && empty($end)){
            return $query;
         }
        $query->dateFilter($created_at, $start, $end);
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
            $start = LyskillsCarbon::parse($startDate)->startOfDay();
            $end = LyskillsCarbon::parse($endDate)->endOfDay();

            $query->dateFilter($created_at, $start, $end);
        }
        return $query;
    }
}
