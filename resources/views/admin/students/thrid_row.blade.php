@php
    $paymentDate = old(
        'payment_date',
        $is_update && $student ? $student?->enrolledCourses()->first()->payments()?->first()?->payment_date : '',
    );
@endphp
<div class="col-md-4 mt-2">
    <label for="status">Student Status</label>
    <select name="status" id="student_status" class="form-control">
        <option value="Enrolled"
            {{ old('status', $is_update && $student->status ? $student->status : '') == 'Enrolled' ? 'selected' : '' }}>
            Enrolled</option>
        <option value="Completed"
            {{ old('status', $is_update && $student->status ? $student->status : '') == 'Completed' ? 'selected' : '' }}>
            Completed</option>
    </select>
</div>

<div class="col-md-4 mt-2" id="drop_reason_box" style="display: none;">
    <label for="drop_reason">Drop Reason</label>
    <textarea name="drop_reason" class="form-control" rows="3" placeholder="Enter reason for dropping">{{ old('drop_reason', $is_update && $student->drop_reason ? $student->drop_reason : '') }}</textarea>
</div>
<div class="col-md-4 mt-2">

    <div class="form-group">
        <label>Payment Date</label>
        <input type="date" name="payment_date" class="form-control datepicker" value="{{ $paymentDate }}">
    </div>
</div>
<div class="col-md-4 mt-2">
    <div class="form-group">
        <label>Registration Date</label>
        <input type="date" name="registration_date" class="form-control datepicker"
            value="{{ old('registration_date', $student->registration_date ?? '') }}">
    </div>
</div>
