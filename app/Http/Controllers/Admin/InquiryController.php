<?php

namespace App\Http\Controllers\Admin;

use App\Classes\InqStatus;
use App\Classes\StartEndDateFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\InquiryRequest;
use App\Models\Course;
use App\Models\Inquiry;
use App\Models\InquiryLog;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class InquiryController extends Controller
{

    public function index(Request $request)
    {


        $query = InqStatus::handle();

        /* ========================
           DATE FILTER
        ======================== */
        $query = StartEndDateFilter::date($request, $query);
        $query = StartEndDateFilter::handle($request, $query);
        $query->when(request('due_date'), function ($q) {
                    $q->whereDate('due_date', request('due_date'));
                });

        if ($request->course_id) {
            $query->where('course_id', $request->course_id);
        }

        $inquiries = $query->get();

        $courses = Course::latestCourse();
        return view('admin.inquiries.index', compact('inquiries', 'courses'));
    }

    public function create()
    {
        return view('admin.inquiries.create');
    }

    public function store(InquiryRequest $request)
    {
        try {
            Inquiry::create(array_merge(
                $request->validated(),
                ['created_by' => Auth::id()]
            ));

            return redirect()->route('inquiries.index')->with('success', "Saved...");;
        } catch (Exception $e) {
            server_logs($e->getMessage());
            return redirect()
                ->route('inquiries.index')
                ->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $inquiry = Inquiry::withTrashed()->findOrFail($id);
        $courses = Course::latest()->get();

        return view('admin.inquiries.edit', compact('inquiry', 'courses'));
    }

    public function update(InquiryRequest $request, $id)
    {
        try {
            $inquiry = Inquiry::findOrFail($id);

            $inquiry->update(array_merge(
                $request->validated(),
                ['updated_by' => Auth::id()]
            ));

            return redirect()->route('inquiries.index')->with('success', "Updated...");;
        } catch (Exception $e) {
            server_logs($e->getMessage());
            return redirect()
                ->route('inquiries.index')
                ->with('error', $e->getMessage());
        }
    }


    public function delete($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        $inquiry->deleted_by = Auth::id();
        $inquiry->save();
        $inquiry->delete();

        return back();
    }
    public function logs($id)
    {
        $logs = InquiryLog::where('inquiry_id', $id)
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.inquiries.logs', compact('logs', 'id'));
    }
}
