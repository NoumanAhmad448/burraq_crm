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
                    <span class="text-primary">Paid Fee:
                        {{ $enrolledCourse?->payments()?->where('is_deleted', false)?->sum('paid_amount') }}</span>
                </div>

                <div class="card-body p-0">
                    <table class="table table-bordered mb-0 datatable">
                        <thead class="table-light">
                            <tr>
                                <th>Action</th>
                                <th>Performed By</th>
                                <th>Old Data</th>
                                <th>New Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($enrolledCourse?->payments as $payment)
                                @foreach ($payment->logs as $log)
                                    <tr>
                                        <td class="text-uppercase">
    @if($log->action === 'created')
        <span class="badge badge-success">Created</span>
    @elseif($log->action === 'updated')
        <span class="badge badge-primary">Updated</span>
    @elseif($log->action === 'deleted')
        <span class="badge badge-danger">Deleted</span>
    @else
        <span class="badge badge-secondary">{{ ucfirst($log->action) }}</span>
    @endif
</td>

<td>
    <strong>{{ $log->performed_by_name }}</strong><br>
    <small>{{ $log->performed_by_email }}</small><br>
    <small class="text-muted">{{ $log->performed_by_role }}</small>
</td>
<td>
    @php $old = $log->old_data ? json_decode($log->old_data, true) : []; @endphp

    @if($old)
        @foreach($old as $key => $value)
            <div>
                <strong>{{ ucwords(str_replace('_',' ', $key)) }}</strong>:
                {{ is_array($value) ? json_encode($value) : $value }}
            </div>
        @endforeach
    @else
        —
    @endif
</td>
<td>
    @php $new = $log->new_data ? json_decode($log->new_data, true) : []; @endphp

    @if($new)
        @foreach($new as $key => $value)
            <div>
                <strong>{{ ucwords(str_replace('_',' ', $key)) }}</strong>:
                {{ is_array($value) ? json_encode($value) : $value }}
            </div>
        @endforeach
    @else
        —
    @endif
</td>


                                    </tr>
                                @endforeach
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
