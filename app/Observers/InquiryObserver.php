<?php

namespace App\Observers;

use App\Models\Inquiry;
use App\Models\InquiryLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class InquiryObserver
{
    public function created(Inquiry $inquiry)
    {
        InquiryLog::create([
            'inquiry_id' => $inquiry->id,
            'action' => 'created',
            'new_data' => $inquiry->toArray(),
            'action_by' => Auth::id(),
            'ip_address' => Request::ip(),
        ]);
    }

    public function updated(Inquiry $inquiry)
    {
        InquiryLog::create([
            'inquiry_id' => $inquiry->id,
            'action' => 'updated',
            'old_data' => $inquiry->getOriginal(),
            'new_data' => $inquiry->getChanges(),
            'action_by' => Auth::id(),
            'ip_address' => Request::ip(),
        ]);
    }

    public function deleted(Inquiry $inquiry)
    {
        InquiryLog::create([
            'inquiry_id' => $inquiry->id,
            'action' => 'deleted',
            'old_data' => $inquiry->toArray(),
            'action_by' => Auth::id(),
            'ip_address' => Request::ip(),
        ]);
    }

    public function restored(Inquiry $inquiry)
    {
        InquiryLog::create([
            'inquiry_id' => $inquiry->id,
            'action' => 'restored',
            'new_data' => $inquiry->toArray(),
            'action_by' => Auth::id(),
            'ip_address' => Request::ip(),
        ]);
    }
}
