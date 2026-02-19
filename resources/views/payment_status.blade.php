@php

    $paid_cond = $course->total_fee - $paid_payment <= 0;
    $overdue_cond =
        \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($course->due_date)) &&
        $course->total_fee - $paid_payment > 0 &&
        empty($course->status);

    $unpaid_cond = $paid_payment > 0 && $course->total_fee - $paid_payment > 0 && empty($course->status);

    $dropped_cond = $course->status == "dropped";
    $refunded_cond = $course->status == "refunded";
@endphp

<td>
    <small
        @if ($paid_cond) class="btn btn-success btn-rounded"
        
        @elseif($overdue_cond) class="btn btn-danger btn-rounded"
        
        @elseif($unpaid_cond) class="btn btn-warning"
        
        @elseif($dropped_cond) class="btn btn-secondary"
        @elseif($refunded_cond) class="btn btn-outline-primary"
        
        @else class="btn btn-danger" @endif>
        {{-- @dump($course->total_fee) --}}
        {{-- @dump($paid_payment) --}}

        @if ($paid_cond)
            Paid
        @elseif($overdue_cond)
            Overdue
        @elseif($unpaid_cond)
            Unpaid
        @elseif($dropped_cond)
            Dropped
        @elseif($refunded_cond)
            Refunded
        @else
            Deleted
        @endif
    </small>
</td>
