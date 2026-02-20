<?php

namespace App\Observers;

use App\Models\Student as CrmStudent;
use App\Models\CrmStudentLog;

class CrmStudentObserver
{
    public function created(CrmStudent $student): void
    {
        $this->log($student, Observer::CREATED);
    }

    public function updated(CrmStudent $student): void
    {
        $this->log($student, Observer::UDPATED);
    }
    public function updating(CrmStudent $student): void
    {
        if ($student->isDirty('is_deleted')) {

            if ($student->is_deleted) {
                $this->log($student, Observer::DELETED);
            }else{
                $this->log($student, Observer::ACTIVED);
            }
        }
    }

    protected function log(CrmStudent $student, string $action): void
    {
        CrmStudentLog::create([
            'crm_student_id' => $student->id,
            'user_id' => auth()->id(), // safe (nullable)
            'action' => $action,
            'student_snapshot' => $student->toArray(),
        ]);
    }
}
