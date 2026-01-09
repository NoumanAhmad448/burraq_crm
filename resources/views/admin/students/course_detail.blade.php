@extends('admin.admin_main')

@section('content')
<div class="container-fluid">

    {{-- STUDENT INFO --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong>Student Information</strong>
        </div>
        <div class="card-body">
            <p><b>Name:</b> {{ $enrolledCourse->student->name }}</p>
            <p><b>Father Name:</b> {{ $enrolledCourse->student->father_name }}</p>
            <p><b>CNIC:</b> {{ $enrolledCourse->student->cnic }}</p>
            <p><b>Mobile:</b> {{ $enrolledCourse->student->mobile }}</p>
            <p><b>Email:</b> {{ $enrolledCourse->student->email }}</p>
        </div>
    </div>

    {{-- COURSE INFO --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong>Course Details</strong>
        </div>
        <div class="card-body">
            <p><b>Course Name:</b> {{ $enrolledCourse->course->name }}</p>
            <p><b>Total Fee:</b> {{ number_format($enrolledCourse->course->fee, 2) }}</p>
            <p><b>Total Paid:</b> {{ number_format($enrolledCourse->totalPaid(), 2) }}</p>
            <p><b>Remaining:</b> {{ number_format($enrolledCourse->remainingAmount(), 2) }}</p>
            <p><b>Admission Date:</b> {{ $enrolledCourse->admission_date }}</p>
            <p><b>Due Date:</b> {{ $enrolledCourse->due_date }}</p>
        </div>
    </div>

    {{-- PAYMENTS --}}
    <div class="card">
        <div class="card-header">
            <strong>Payments</strong>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($enrolledCourse->payments as $key => $payment)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $payment->payment_date }}</td>
                            <td>{{ number_format($payment->amount, 2) }}</td>
                            <td>{{ $payment->method }}</td>
                            <td>{{ $payment->note }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No payments found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
