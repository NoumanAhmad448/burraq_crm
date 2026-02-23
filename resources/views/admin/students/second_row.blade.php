<div class="col-md-4 mt-2">
    <label>Mobile *</label>
    <input type="text" name="mobile" class="form-control" placeholder="Mobile No. (Mandatory)"
        value="@if ($is_update) {{ $student->mobile }}@else{{ old('mobile') }} @endif">
</div>

<div class="col-md-4 mt-2">
    <label>Email</label>
    <input type="email" name="email" class="form-control" placeholder="Email Address"
        value="@if ($is_update) {{ $student->email }}@else{{ old('email') }} @endif">
</div>

@php
    $selectedMethod = old(
        'payment_method',
        $is_update && $student
            ? $student?->enrolledCourses()->first()->payments()?->first()?->payment_method ?? null
            : null,
    );

    $payment_slip_path =
        $is_update && $student ? $student?->enrolledCourses()->first()->payments()?->first()?->payment_slip_path : '';
@endphp

<div class="col-md-4 mt-2">
    <label>Payment Method</label>

    <select name="payment_method" class="form-control">
        <option value="">Select Method</option>
        <option value="cash" {{ $selectedMethod === 'cash' ? 'selected' : '' }}>Cash</option>
        <option value="bank" {{ $selectedMethod === 'bank' ? 'selected' : '' }}>Bank Transfer</option>
        <option value="online" {{ $selectedMethod === 'online' ? 'selected' : '' }}>Online</option>
        <option value="easypaisa" {{ $selectedMethod === 'easypaisa' ? 'selected' : '' }}>Easypaisa</option>
        <option value="jazzcash" {{ $selectedMethod === 'jazzcash' ? 'selected' : '' }}>Jazzcash</option>
    </select>
</div>
