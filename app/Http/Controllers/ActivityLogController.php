<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\Group;
use App\Models\GroupCourseProgress;
use App\Models\GroupEnrollment;

class ActivityLogController extends Controller
{

    public function groupLogs($group_id)
    {
        $logs = ActivityLog::with("user")->where('model_type', Group::class)
            ->where("model_id", $group_id)
            ->latest()
            ->get();

        return view('admin.logs.groups', compact('logs'));
    }

    public function moduleLogs( $module_id)
    {
        $logs = ActivityLog::where('model_type', GroupCourseProgress::class)
            ->where("model_id", $module_id)
            ->latest()
            ->get();

        // dd($logs);

        return view('admin.logs.groups', compact('logs'));
    }

    public function groupEnrollmentLogs($group_id)
    {
        $logs = ActivityLog::where('model_type', GroupEnrollment::class)
            ->where("model_id", $group_id)

            ->latest()
            ->get();

        return view('admin.logs.groups', compact('logs'));
    }
}
