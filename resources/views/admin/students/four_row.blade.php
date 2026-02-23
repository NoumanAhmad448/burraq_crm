@if (false)
    <div class="col-md-4 mt-2">
        <label>Admission Date</label>
        <input type="text" name="admission_date" class="form-control datepicker"
            value="@if ($is_update) {{ old('admission_date', $student?->admission_date) }} @endif">
    </div>
    <div class="col-md-4 mt-2">
        <label>Due Date </label>
        <input type="text" name="due_date" class="form-control datepicker"
            value="@if ($is_update) {{ old('due_date', dateFormat($student->admission_date)) }} @endif">
    </div>

    <div class="col-md-4 mt-2">
        <label>Total Fee </label>
        <input type="text" name="total_fee" class="form-control" required step="any"
            value="@if ($is_update) {{ (int) $student->total_fee }}@else{{ old('total_fee') }} @endif">
    </div>
    <div class="col-md-4 mt-2">
        <label>Paid Fee </label>
        <input type="text" name="paid_fee" class="form-control" required step="0.01"
            value="@if ($is_update) {{ (int) $student->paid_fee }}@else{{ old('paid_fee') }} @endif">
    </div>
@endif
<div class="col-md-4 mt-2">
    <label>Photo</label>
    @include('file', ['name' => 'photo'])
</div>
<div class="col-md-4 mt-2">
    <label>Payment Slip</label>
    @include('file', ['name' => 'payment_slip_path'])
    <br />
    @if ($is_update && $student->payment_slip_path)
        <a href="{{ asset(img_path($payment_slip_path)) }}" target="_blank" class="text-primary underscore">View Current
            Slip</a>
    @endif
</div>
