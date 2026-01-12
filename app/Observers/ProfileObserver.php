<?php

namespace App\Observers;

use App\Models\Profile;
use App\Models\UserLog;

class ProfileObserver
{
    private function sanitizeProfileData(Profile $profile)
    {
        $profileData = $profile->toArray();

        return collect($profileData)->except([
            'id',
            'password',
            "is_admin",
            "resume_path",
            "cnic_photo_path",
            "other_document_path",
            "profile_photo_path",
        ])->toArray();
    }
    public function created(Profile $profile)
    {

        UserLog::create([
            'user_id'      => $profile->user_id,
            'performed_by' => auth()->id(),
            'module'       => 'HR',
            'action'       => 'create',
            'model'        => 'Profile',
            'record_id'    => $profile->id,
            'new_values'   => $this->sanitizeProfileData($profile),
        ]);
    }

    public function updated(Profile $profile)
    {
        UserLog::create([
            'user_id'      => $profile->user_id,
            'performed_by' => auth()->id(),
            'module'       => 'HR',
            'action'       => 'update',
            'model'        => 'Profile',
            'record_id'    => $profile->id,
            'old_values'   => $this->sanitizeProfileData($profile->getOriginal()),
            'new_values'   => $profile->getChanges(),
        ]);
    }

    public function deleted(Profile $profile)
    {
        UserLog::create([
            'user_id'      => $profile->user_id,
            'performed_by' => auth()->id(),
            'module'       => 'HR',
            'action'       => 'delete',
            'model'        => 'Profile',
            'record_id'    => $profile->id,
            'old_values'   => $this->sanitizeProfileData($profile->getOriginal()),
        ]);
    }
}
