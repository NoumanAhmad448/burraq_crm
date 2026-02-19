<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EnrolledCourse;

class EnrolledCourseController extends Controller
{
    public function updateStatus(Request $request, $id)
    {
        $enrollment = EnrolledCourse::with('payments')->where("id", $id);
        $total_paid = $enrollment->first()->payments()->totalPaid();
        // dd($enrollment);
        $request->validate([
            'status' => 'nullable|in:active,dropped,refunded,completed',
            'status_note' => 'nullable|string'
        ]);

        // 🔴 If Dropped → require message
        if ($request->status === EnrolledCourse::DROPPED && empty($request->status_note)) {
            return back()->withErrors([
                'status_note' => 'Message is required when dropping a course.'
            ]);
        }

        // 🔴 If Refunded → ensure at least one refunded payment exists
        if ($request->status === EnrolledCourse::REFUNDED && !$enrollment->canBeRefunded()) {
            return back()->withErrors([
                'status' => 'Cannot mark as refunded. No refunded payment found.'
            ]);
        }

        if ($request->status === EnrolledCourse::DROPPED && $enrollment->first()->total_fee <= $total_paid) {
            return back()->withErrors([
                'status' => 'Cannot drop student. Full payment already completed.'
            ])->withInput();
        }


        $enrollment->update([
            'status' => $request->status,
            'status_note' => $request->status_note,
            'status_updated_at' => now(),
        ]);

        return back()->with('success', 'Status updated successfully.');
    }

    public function editStatus(EnrolledCourse $enrolledCourse)
    {
        return view('enrolled-courses.edit-status', compact('enrolledCourse'));
    }
}
