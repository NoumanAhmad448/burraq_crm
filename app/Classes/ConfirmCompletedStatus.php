<?php

namespace App\Classes;

use App\Models\EnrolledCourse;
use Illuminate\Support\Collection;

class ConfirmCompletedStatus
{
    protected int $studentId;

    public function __construct(int $studentId)
    {
        $this->studentId = $studentId;
    }

    /**
     * Returns courses that are NOT fully paid
     *
     * @return \Illuminate\Support\Collection
     */
    public function handle(): Collection
    {
        return EnrolledCourse::with([
                'payments' => fn ($q) => $q->where('is_deleted', 0),
            ])
            ->where('is_deleted', 0)
            ->whereHas('student', fn ($q) =>
                $q->where('is_deleted', 0)
                  ->where('id', $this->studentId)
            )
            ->get()
            ->filter(function ($course) {
                $totalPaid = $course->payments->sum('paid_amount');
                return $totalPaid < $course->total_fee; // ❌ incomplete
            })
            ->values();
    }
}
