<?php

namespace App\Observers;

use App\Models\GroupEnrollment;

class GroupEnrollmentObserver
{
    public function updating(GroupEnrollment $group)
    {
        $action = Observer::UDPATED;

        Observer::logActivity([
            'action'     => $action,
            'model_type' => GroupEnrollment::class,
        ], $group);
    }

    public function created(GroupEnrollment $group)
    {
        Observer::logActivity([
            'action'     => Observer::CREATED,
            'model_type' => GroupEnrollment::class,
        ], $group);
    }

    public function deleted(GroupEnrollment $group)
    {
        Observer::logActivity([
            'action'     => Observer::DELETED,
            'model_type' => GroupEnrollment::class,
        ], $group);
    }
}
