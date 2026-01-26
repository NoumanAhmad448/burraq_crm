<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Contracts\InquiryDashboardContract as ContractsInquiryDashboardContract;

class InquiryDashboardController extends Controller
{
    public function index(ContractsInquiryDashboardContract $dashboard)
    {
        $data = $dashboard->data();
        // dd($data);
        return view('admin.inquiry.dashboard', compact('data'));
    }
}
