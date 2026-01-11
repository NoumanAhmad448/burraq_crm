@extends('admin.admin_main')

@section('page-css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="container">


        @include('admin.course_payments.payment_form', [
            'is_update' => false,
            'enrolledCourse' => $enrolledCourse,
            'student_id' => $student_id,
            'enrolled_course_id' => $enrolled_course_id,
        ])

        {{-- Payments List --}}
        <h4 class="mt-5">Payments List</h4>

        <table class="table table-bordered mt-2">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Amount Paid</th>
                    <th>Payment Slip</th>
                    <th>By</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $index => $payment)
                    <tr @if ($payment->is_deleted) style="color:red;" @endif>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ number_format($payment->paid_amount, 2) }}</td>
                        <td>
                            @if ($payment->payment_slip_path)
                                <a href="{{ asset(img_path($payment->payment_slip_path)) }}" target="_blank">View Slip</a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $payment->paidBy->name ?? 'N/A' }}</td>
                        <td>{{ \App\Classes\LyskillsCarbon::parse($payment->created_at)->toFormattedDateString() }}</td>
                        <td>
                            <a href={{ route("course_payments.get", [
                                    'student_id' => $student_id,
                                    'enrolledCourseId' => $enrolled_course_id,
                                    'payment_id' => $payment->id
                                    ])}} class="btn btn-sm btn-outline-info"
                            >
                            Edit
                            </a>
                            @if (!$payment->is_deleted || auth()->user()->is_admin)
                                <form action="{{ route('course_payments.delete', $payment->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
