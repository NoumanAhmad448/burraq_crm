@php
    $paid_amount = $enrolledCourse?->payments()->where('is_deleted', 0)->sum('paid_amount');
@endphp
<div class="form-group mx-auto">
    <table class="table table-bordered w-50">
        <thead class="thead-light">
            <tr>
                <th>Detail</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Course Name</td>
                <td>{{ $enrolledCourse?->course?->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>Total Price</td>
                <td>{{ show_payment($enrolledCourse?->total_fee) }}</td>
            </tr>
            <tr>
                <td>Paid Amount</td>
                <td>{{ show_payment($paid_amount) }}</td>
            </tr>
            <tr>
                <td>Remaining Payment</td>
                <td>{{ show_payment($enrolledCourse?->total_fee - $paid_amount) }}</td>
            </tr>
        </tbody>
    </table>
</div>

<h3>
    @if ($is_update)
        Update
    @else
        Add
    @endif Payment
    @if ($is_update)
        <a href="{{ route('students.course.payments', ['student_id' => $student_id, 'enrolledCourseId' => $enrolled_course_id]) }}"
            class="btn btn-sm btn-outline-success" title="Course -> Payments">
            <i class="fa fa-credit-card"></i> Check Previous Payments
        </a>
    @endif
</h3>
<hr />

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('course_payments.store') }}" method="POST" enctype="multipart/form-data">
    @csrf


<input type="hidden" name="enrolled_course_id" class="form-control" placeholder="Enter Enrolled Course ID if exists"
        value="{{ $enrolledCourse->id }}" required>

    <div class="form-group">
        <label>Student</label>
        <select name="student_id" class="form-control" required>
            <option value="">Select Student</option>
            @foreach ($students as $student)
                <option value="{{ $student->id }}" @if (old('student_id') == $student->id || $student_id == $student->id) selected @endif>
                    {{ $student->name }}</option>
            @endforeach
        </select>
        {{-- {{dd($is_update)}} --}}
    </div>

    @if ($is_update)
        <input type="hidden" name="payment_id" value="{{ $payment->id }}">
    @endif


    <div class="form-group">
        <label>Paid Amount</label>
        <input type="text" step="0.01" name="paid_amount" class="form-control" required
            value="{{ old('paid_amount', $is_update ? (int) $payment->paid_amount : '') }}">
    </div>

    @php
        $selectedMethod = old(
            'payment_method',
            ($is_update ?? false) ? ($payment->payment_method ?? null) : null
        );
    @endphp
    @php
        $paymentDate = old(
            'payment_date',
            ($is_update ?? false)
                ? $payment->payment_date
                : ""
        );
    @endphp


    <div class="form-group">
        <label>Payment Slip (Optional)</label>
        <label class="file-upload-card">

            <input type="file" name="payment_slip" class="form-control" hidden>
            @include('file', ['name' => 'payment_slip'])
        </label>
        @if ($is_update && $payment->payment_slip_path)
            <div class="mt-2">
                <a href="{{ asset(img_path($payment->payment_slip_path)) }}" target="_blank">View Existing
                    Slip</a>
            </div>
        @endif
    </div>
    @if ($enrolledCourse?->total_fee - $paid_amount > 0)
        <button type="submit" class="btn btn-primary">
            @if ($is_update)
                Update
            @else
                Add
            @endif Payment
        </button>
    @endif
</form>
