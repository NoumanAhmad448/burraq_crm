@extends('admin.admin_main')

@section('content')
<div class="container-fluid">

    <h4>{{ $student->name }} ({{ $student->father_name }})</h4>

    <hr>

    @foreach($student->enrolledCourses as $enrolled)
        <div class="card mb-3">
            <div class="card-header">
                {{ \Illuminate\Support\Str::limit($enrolled->course->name, 30) }}
            </div>

            <div class="card-body">
                <p><strong>Course Fee:</strong> {{ $enrolled->course->fee }}</p>

                <h6>Payments</h6>
                <table class="table table-sm crm_table_payments">
                    <thead>
                        <tr>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enrolled->payments as $payment)
                            <tr>
                                <td>{{ $payment->amount }}</td>
                                <td>{{ $payment->paid_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    @endforeach

</div>
@endsection
