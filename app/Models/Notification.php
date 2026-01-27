<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'type',
        'count',
        'route',
        'meta',
    ];

    protected $casts = [
        'route' => 'array',
        'meta'  => 'array',
    ];

    public function reads()
    {
        return $this->hasMany(NotificationRead::class);
    }

    public function unreadForUser($userId)
    {
        return !$this->reads()
            ->where('user_id', $userId)
            ->whereNotNull('read_at')
            ->exists();
    }

    /**
     * Check if the notification has been read by a given user
     *
     * @param int $userId
     * @return bool
     */
    public function readForUser($userId)
    {
        return $this->reads()
            ->where('user_id', $userId)
            ->exists();
    }
}
