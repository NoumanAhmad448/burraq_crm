<?php

namespace App\Observers;

use App\Models\GroupCourseProgress;

class GroupCourseProgressObserver
{
    /**
     * Handle the GroupCourseProgress "created" event.
     *
     * @param  \App\Models\GroupCourseProgress  $groupCourseProgress
     * @return void
     */
    public function created(GroupCourseProgress $groupCourseProgress)
    {
         Observer::logActivity([
            'action'     => Observer::CREATED,
            'model_type' => GroupCourseProgress::class,
        ], $groupCourseProgress);
    }

    /**
     * Handle the GroupCourseProgress "updated" event.
     *
     * @param  \App\Models\GroupCourseProgress  $groupCourseProgress
     * @return void
     */
    public function updating(GroupCourseProgress $groupCourseProgress)
    {
        $action = Observer::UDPATED;

        Observer::logActivity([
            'action'     => $action,
            'model_type' => GroupCourseProgress::class,
        ], $groupCourseProgress);
    }

    /**
     * Handle the GroupCourseProgress "deleted" event.
     *
     * @param  \App\Models\GroupCourseProgress  $groupCourseProgress
     * @return void
     */
    public function deleted(GroupCourseProgress $groupCourseProgress)
    {
        Observer::logActivity([
            'action'     => Observer::DELETED,
            'model_type' => GroupCourseProgress::class,
        ], $groupCourseProgress);
    }

    /**
     * Handle the GroupCourseProgress "restored" event.
     *
     * @param  \App\Models\GroupCourseProgress  $groupCourseProgress
     * @return void
     */
    public function restored(GroupCourseProgress $groupCourseProgress)
    {
        //
    }

    /**
     * Handle the GroupCourseProgress "force deleted" event.
     *
     * @param  \App\Models\GroupCourseProgress  $groupCourseProgress
     * @return void
     */
    public function forceDeleted(GroupCourseProgress $groupCourseProgress)
    {
        //
    }
}
