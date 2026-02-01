<?php

namespace App\Console\Commands;

use App\Models\Inquiry;
use App\Models\Notification;
use Carbon\Carbon;

class FollowUpInquiryDueTodayCron extends BaseCron
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:follow-up-inquiries-due-today';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify follow-up inquiries whose due date is today';

    /**
     * Execute the console command.
     */
    public function runCron()
    {
        $count = Inquiry::query()
            ->where('status', 'follow_up')
            ->whereDate('due_date', Carbon::today())
            ->count();

        if ($count > 0) {
            Notification::create([
                'type'  => 'follow_up_inquiries',
                'count' => $count,
                'route' => [
                    'route' => 'inquiries.index',
                    'route_keys' => [
                        'type' => 'follow_up',
                        'due_date' => Carbon::today()->toDateString(),
                    ],
                ],
            ]);
        }
    }
}
