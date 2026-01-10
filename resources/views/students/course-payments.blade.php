@extends('admin.admin_main')

@section('content')
<div class="container">

<h4 class="mb-4">
    {{ $student->name }} — Enrolled Courses & Payments
</h4>

@foreach ($enrolledCourses as $enrolledCourse)

<div class="card mb-4 shadow-sm">
    <div class="card-header d-flex justify-content-between">
        <strong>{{ $enrolledCourse->course->name }}</strong>
        <span>Total Fee: {{ $enrolledCourse->total_fee }}</span>
    </div>

    <div class="card-body p-0">
        <table class="table table-bordered mb-0 datatable">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Paid Amount</th>
                    <th>Paid At</th>
                    <th>Payment By</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($enrolledCourse->payments as $payment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $payment->paid_amount }}</td>
                        <td>{{ $payment->paid_at }}</td>
                        <td>{{ $payment->paidBy?->name ?? 'System' }}</td>
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

</div>
@endsection

<script>
$(document).ready(function () {
    $('.datatable').DataTable({
        pageLength: 10,
        ordering: true,
        searching: true,
        lengthChange: false,
        order: [[0, 'asc']],
        language: {
            search: "",
            searchPlaceholder: "Search records"
        }
    });
});
</script>
