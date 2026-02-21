<?php

namespace App\Observers;

use App\Models\Group;

class ModuleObserver
{
    public function updating(Group $group)
    {
        $action = Observer::UDPATED;

        Observer::logActivity([
            'action'     => $action,
            'model_type' => Group::class,
        ], $group);
    }

    public function created(Group $group)
    {
        Observer::logActivity([
            'action'     => Observer::CREATED,
            'model_type' => Group::class,
        ], $group);
    }

    public function deleted(Group $group)
    {
        Observer::logActivity([
            'action'     => Observer::DELETED,
            'model_type' => Group::class,
        ], $group);
    }
}
