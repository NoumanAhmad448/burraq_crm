<?php

namespace App\Http\Controllers\Admin;

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

    public function index()
    {
        $inquiries = Inquiry::withTrashed()->latest()->get();
        $courses = Course::all();
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

            debug_logs('Inquiry created', $request->all());

            return redirect()->route('inquiries.index')->with('success', "Saved...");;
        } catch (Exception $e) {
            // dd($e->getMessage());
            return redirect()
                ->route('inquiries.index')
                ->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $inquiry = Inquiry::withTrashed()->findOrFail($id);
        $courses = Course::all();

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

            debug_logs('Inquiry updated', $request->all());

            return redirect()->route('inquiries.index')->with('success', "Updated...");;
        } catch (Exception $e) {
            // dd($e->getMessage());
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

        debug_logs('Inquiry deleted', ['id' => $id]);

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
