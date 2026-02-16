<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Contracts\InquiryDashboardContract as ContractsInquiryDashboardContract;
use App\Models\Inquiry;
use App\Models\Course;

class InquiryDashboardController extends Controller
{
    public function index(ContractsInquiryDashboardContract $dashboard)
    {
        $data = $dashboard->data();
        // dd($data);
        return view('admin.inquiry.dashboard', compact('data'));
    }


    public function dashboard()
    {
        // Group inquiries by course_id and count
        $inquiryCounts = Inquiry::selectRaw('course_id, COUNT(*) as total')
            ->groupBy('course_id')
            ->with('course')
            ->get();

        return view('admin.inquiries.dashboard', compact('inquiryCounts'));
    }
}
