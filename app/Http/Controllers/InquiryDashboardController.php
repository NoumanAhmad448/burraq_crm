<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Contracts\InquiryDashboardContract as ContractsInquiryDashboardContract;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use App\Classes\LyskillsCarbon;
use App\Classes\StartEndDateFilter;

class InquiryDashboardController extends Controller
{
    public function index(ContractsInquiryDashboardContract $dashboard)
    {
        $data = $dashboard->data();
        // dd($data);
        return view('admin.inquiry.dashboard', compact('data'));
    }


    public function dashboard(Request $request)
    {
        $query = Inquiry::query();

        /* ========================
           COURSE FILTER
        ======================== */
        if ($request->course_id) {
            $query->where('course_id', $request->course_id);
        }

        /* ========================
           DATE FILTER
        ======================== */
        $query = StartEndDateFilter::date($request, $query);
        $query = StartEndDateFilter::handle($request, $query);

        // Group inquiries by course_id and count
        $query = $query->selectRaw('course_id, COUNT(*) as total')
            ->groupBy('course_id')
            ->with('course');
        // \printQuery($query);

        $inquiryCounts = $query->get();

        return view('admin.inquiries.dashboard', compact('inquiryCounts'));
    }
}
