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

        $routes = [
            [
                "route" => route("admin.groups.index"),
                "msg" => "Back To Group Module",
                "color" => "btn-success",
            ]
        ];
        
        return view('admin.logs.groups', compact('logs', "routes"));
    }

    public function moduleLogs($module_id)
    {
        $logs = ActivityLog::where('model_type', GroupCourseProgress::class)
            ->where("model_id", $module_id)
            ->latest()
            ->get();

        // dd();
        $routes = [
            [
                "route" => route("admin.groups.index"),
                "msg" => "Back To Group Module",
                "color" => "btn-success",
            ],
            [
                "route" => route("admin.group.modules", request()->segment(4)),
                "msg" => "Back To Module",
                "color" => "btn-primary",
            ]
        ];

        return view('admin.logs.groups', compact('logs', "routes"));
    }

    public function groupEnrollmentLogs($id)
    {
        $logs = ActivityLog::where('model_type', GroupEnrollment::class)
            ->where("new_values->group_id", $id)
            ->latest()
            ->get();

        $routes = [
            [
                "route" => route("admin.groups.index"),
                "msg" => "Back To Group Module",
                "color" => "btn-success",
            ],
            [
                "route" => route("admin.group.students", request()->segment(4)),
                "msg" => "Back To Group Students",
                "color" => "btn-warning",
            ]
        ];
            
        return view('admin.logs.groups', compact('logs', "routes"));
    }
}
