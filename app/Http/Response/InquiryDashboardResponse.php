<?php

namespace App\Http\Response;

use App\Classes\Inquiry\InquiryContacted;
use App\Classes\Inquiry\InquiryFollowUp;
use App\Classes\Inquiry\InquiryNotContacted;
use App\Classes\Inquiry\InquiryNotInterested;
use App\Classes\Inquiry\InquiryPending;
use App\Classes\Inquiry\InquiryThisMonthContacted;
use App\Classes\Inquiry\InquiryThisMonthPending;
use App\Classes\Inquiry\InquiryTotal;
use App\Classes\InquiryMonthlyCount;
use App\Classes\ResponseKeys;
use App\Classes\InquiryDashboardResponse as IDR;
use App\Http\Contracts\FaqContract;
use App\Http\Contracts\InquiryDashboardContract;
use Exception;
use App\Models\Faq;

class InquiryDashboardResponse implements InquiryDashboardContract
{
    public function data(): array
    {
        $month = now()->month;
        $year  = now()->year;

        return [
            'total'             => InquiryTotal::get(),
            'pending'           => InquiryPending::get(),
            'contacted'         => InquiryContacted::get(),
            'follow_up'         => InquiryFollowUp::get(),
            'not_interested'    => InquiryNotInterested::get(),
            'this_month_pending'=> InquiryThisMonthPending::get($month, $year),
            'this_month_contact'=> InquiryThisMonthContacted::get($month, $year),
            'not_contacted'     => InquiryNotContacted::get(),
            "monthlyCount" => InquiryMonthlyCount::get($year),
            "statusWise"   => IDR::getStatusWise(),
        ];
    }
}
