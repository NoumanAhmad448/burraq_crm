<td>
    <small
        @if ($course->total_fee - $paid_payment <= 0) class="btn btn-success btn-rounded"
        @elseif(\Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($course->due_date)) && $course->total_fee - $paid_payment > 0 &&
         !$refunded_payment->count() > 0) class="btn btn-danger btn-rounded"
        @elseif($paid_payment > 0 && $course->total_fee - $paid_payment > 0 && !$refunded_payment->count() > 0) class="btn btn-warning"
        @elseif($refunded_payment->count() > 0) class="btn btn-secondary"
        @else class="btn btn-danger" @endif>
        {{-- @dump($course->total_fee) --}}
        {{-- @dump($paid_payment) --}}
        @if ($course->total_fee <= $paid_payment)
            Paid
        @elseif(\Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($course->due_date)) && $paid_payment < $course->total_fee && !$refunded_payment->count() > 0)
            Overdue
        @elseif($paid_payment > 0 && $course->total_fee > $paid_payment && !$refunded_payment->count() > 0)
            Unpaid
        @elseif($refunded_payment->count() > 0)
            Refunded
        @else
            Deleted
        @endif
    </small>
</td>
