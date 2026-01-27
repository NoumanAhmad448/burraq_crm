<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationRead;
use Illuminate\Support\Facades\Auth;
use App\Classes\LyskillsCarbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function markRead(Request $request)
    {
        $request->validate([
            'notification_id' => 'required|integer'
        ]);

        NotificationRead::firstOrCreate(
            [
                'notification_id' => $request->notification_id,
                'user_id'         => Auth::id(),
            ],
            [
                'read_at' => LyskillsCarbon::now(),
            ]
        );

        // Intentionally return nothing useful
        return response()->noContent(); // 204
    }
}
