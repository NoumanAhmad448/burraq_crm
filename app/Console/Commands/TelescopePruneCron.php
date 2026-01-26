<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CronJobs;
use App\Classes\LyskillsCarbon;
use Illuminate\Support\Facades\Artisan;
use Exception;

class TelescopePruneCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:telescope-prune';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Custom cron wrapper for Telescope prune with status tracking';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Ensure cron row exists
        $cron_job = CronJobs::firstOrCreate(
            [
                config('table.name') => $this->signature
            ],
            [
                config('table.name')   => $this->signature,
                config('table.status') => config('constants.idle'),
                config('table.w_name') => config('app.url'),
                config('table.starts_at') => LyskillsCarbon::now()
            ]
        );

        try {
            // Run Telescope prune
            Artisan::call('telescope:prune');

            // If success, update the cron table
            $cron_job->update([
                config('table.status') => config('constants.completed'),
                'last_run_at' => LyskillsCarbon::now(),
            ]);

            $this->info("Telescope prune ran successfully and cron updated.");

        } catch (Exception $e) {
            // If error, mark cron as failed
            $cron_job->update([
                config('table.status') => config('constants.failed'),
                'last_run_at' => LyskillsCarbon::now(),
                'error_message' => $e->getMessage(),
            ]);

            $this->error("Telescope prune failed: " . $e->getMessage());
        }
    }
}
