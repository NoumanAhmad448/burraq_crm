<?php

namespace App\Observers;

use App\Models\User;
use App\Models\UserLog;

class UserObserver
{
    public function created(User $user)
    {
        UserLog::create([
            'user_id'      => $user->id,
            'performed_by' => auth()->id(),
            'module'       => $user->role === 'hr' ? 'HR' : 'USERS',
            'action'       => 'create',
            'model'        => 'User',
            'record_id'    => $user->id,
            'new_values'   => $user->toArray(),
        ]);
    }

    public function updated(User $user)
    {
        UserLog::create([
            'user_id'      => $user->id,
            'performed_by' => auth()->id(),
            'module'       => $user->role === 'hr' ? 'HR' : 'USERS',
            'action'       => 'update',
            'model'        => 'User',
            'record_id'    => $user->id,
            'old_values'   => $user->getOriginal(),
            'new_values'   => $user->getChanges(),
        ]);
    }

    public function deleted(User $user)
    {
        UserLog::create([
            'user_id'      => $user->id,
            'performed_by' => auth()->id(),
            'module'       => $user->role === 'hr' ? 'HR' : 'USERS',
            'action'       => 'delete',
            'model'        => 'User',
            'record_id'    => $user->id,
            'old_values'   => $user->getOriginal(),
        ]);
    }
}
