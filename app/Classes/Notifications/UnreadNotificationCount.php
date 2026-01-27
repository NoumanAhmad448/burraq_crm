<?php

namespace App\Classes\Notifications;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class UnreadNotificationCount
{
    public static function get(): int
    {
        $userId = Auth::id();

        return Notification::whereDoesntHave('reads', function ($q) use ($userId) {
            $q->where('user_id', $userId)
            ->whereNotNull('read_at');
        })->count();
    }
}
