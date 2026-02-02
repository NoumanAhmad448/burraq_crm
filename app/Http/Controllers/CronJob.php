<?php

namespace App\Http\Controllers;

use App\Models\CronJobs;
use Illuminate\Http\Request;

class CronJob extends Controller
{
    public function getCronJob()
    {
        $cron_jobs = CronJobs::all();
        return view('dev.cron_jobs.get_cron_jobs', compact('cron_jobs'));
    }
}
