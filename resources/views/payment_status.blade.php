@php

    $paid_cond = $course->total_fee - $paid_payment <= 0;
    $overdue_cond =
        \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($course->due_date)) &&
        $course->total_fee - $paid_payment > 0 &&
        empty($course->status);

    $unpaid_cond = $paid_payment > 0 && $course->total_fee - $paid_payment > 0 && empty($course->status) || $course->status == App\Models\EnrolledCourse::ACTIVE;

    $dropped_cond = $course->status == App\Models\EnrolledCourse::DROPPED;
    $refunded_cond = $course->status == App\Models\EnrolledCourse::REFUNDED;
@endphp

<td>
    <small
        @if ($paid_cond) class="btn btn-success btn-rounded"
        
        @elseif($overdue_cond) class="btn btn-danger btn-rounded"
        
        @elseif($unpaid_cond) class="btn btn-warning"
        
        @elseif($dropped_cond) class="btn btn-secondary"
        @elseif($refunded_cond) class="btn btn-outline-primary"
        @elseif($course->is_deleted == 1 || $course->student->is_deleted == 1) class="btn btn-danger"
        
        @else class="btn btn-primary" @endif>
        {{-- @dump($course->total_fee) --}}
        {{-- @dump($paid_payment) --}}

        @if ($paid_cond)
            Paid
        @elseif($overdue_cond)
            Overdue
        @elseif($unpaid_cond)
            Unpaid
        @elseif($dropped_cond)
            {{ humanize(App\Models\EnrolledCourse::DROPPED) }}
        @elseif($refunded_cond)
            {{ humanize(App\Models\EnrolledCourse::REFUNDED) }}
        @elseif($course->is_deleted == 1 || $course->student->is_deleted == 1)
            {{ humanize(App\Models\EnrolledCourse::DELETED) }}
        @else
            {{ humanize($course->status) }}
        @endif
    </small>
</td>
