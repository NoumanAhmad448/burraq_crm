<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\CronJobs;
use App\Classes\LyskillsCarbon;
use Illuminate\Support\Facades\Storage;

class CheckUrlAccessibility extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:url-accessibility';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the accessibility of URLs (images and videos) by verifying their HTTP status codes.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cron_job = CronJobs::firstOrCreate(
            [
                config('table.name') => $this->signature
            ],
            [
                config('table.name') => $this->signature,
                config('table.status') => config('constants.idle'),
                config('table.w_name') => config('app.url'),
                config('table.starts_at') => LyskillsCarbon::now()
            ]
        );
        // Define the URLs to check

        // Check each URL

        try {
            Storage::disk('s3')->getAdapter();
            // ✅ SUCCESS
            $cron_job->update([
                config('table.status')     => config('constants.success'),
                config('table.ends_at')    => LyskillsCarbon::now(),
                config('table.message')    => 'S3 connection successful'
            ]);

            $this->info('S3 connection OK');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            $cron_job->update([
                config('table.status') => config('constants.error'),
                config('table.message') => $e->getMessage(),
                config('table.ends_at') => LyskillsCarbon::now(),
            ]);
        }
    }
}
