<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CronJobs;

class CronJobController extends Controller
{
    public function index()
    {
        $cronJobs = CronJobs::orderBy('starts_at', 'desc')->get();
        return view('admin.cron_jobs.index', compact('cronJobs'));
    }
}
