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

    $type = request('type', 'all');

    $query = Inquiry::withTrashed()->latest();


        switch ($type) {
        case 'pending':
            $query->where('status', 'pending');
            break;

        case 'contacted':
            $query->where('status', 'contacted');
            break;

        case 'follow_up':
            $query->where('status', 'follow_up');
            break;

        case 'not_interested':
            $query->where('status', 'not_interested');
            break;

        case 'not_contacted':
            $query->whereNull('status');
            break;

        // month-based (later refinement)
        case 'this_month_pending':
            $query->where('status', 'pending')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
            break;

        case 'this_month_contacted':
            $query->where('status', 'contacted')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
            break;

        case 'all':
        default:
            // no filter
            break;
    }

    $inquiries = $query->get();

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
