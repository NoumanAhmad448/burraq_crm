@extends('admin.admin_main')

@section('content')
<div class="container">
<a href="{{ route('students.index') }}" class="btn btn-secondary mb-3">
    <i class="fa fa-arrow-left"></i> Back to Students
</a>
<h4 class="mb-4">
    {{ $student->name }} — Enrolled Courses & Payments
</h4>

@foreach ($enrolledCourses as $enrolledCourse)

<div class="card mb-4 shadow-sm">
    <div class="card-header d-flex justify-content-between">
        <strong>{{ $enrolledCourse->course->name }}</strong>
        <span class="text-success">Total Fee: {{ $enrolledCourse->total_fee }}</span>
        <span class="text-primary">Paid Fee: {{ $enrolledCourse?->payments()
    ?->where('is_deleted', false)
    ?->sum('paid_amount') }}</span>
    </div>

    <div class="card-body p-0">
        <table class="table table-bordered mb-0 datatable">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Paid Amount</th>
                    <th>Paid At</th>
                    <th>Payment By</th>
                    <th>Payment Slip</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($enrolledCourse->payments as $payment)
                    <tr @if($payment->is_deleted) class="table-danger" @endif>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $payment->paid_amount }}</td>
                        <td>{{ $payment->paid_at }}</td>
                        <td>{{ $payment->paidBy?->name ?? 'System' }}</td>
                        <td>
                            @if ($payment->payment_slip_path)
                                <a href="{{ asset(img_path($payment->payment_slip_path)) }}" target="_blank">View Slip</a>
                            @else
                                No Slip
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            No payments recorded
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endforeach
<a href="{{ route('students.index') }}" class="btn btn-secondary mb-3">
    <i class="fa fa-arrow-left"></i> Back to Students
</a>
</div>
@endsection

@section('page-js')
    <script>
        $(document).ready(function() {
            new simpleDatatables.DataTable(".datatable", {
                searchable: true,
                perPage: 10
            });

        });
    </script>
@endsection
