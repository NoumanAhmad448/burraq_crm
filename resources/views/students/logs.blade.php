@extends('admin.admin_main')

@section('content')
<div class="container">
    <a href="{{ route('students.index') }}" class="btn btn-secondary mb-3">
    <i class="fa fa-arrow-left"></i> Back to Students
</a>

    <h4 class="mb-4">
        Logs for: {{ $student->name }} (ID: {{ $student->id }})
    </h4>

    <table id="studentLogs" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Action</th>
                <th>Updated By</th>
                <th>Date</th>
                <th>Snapshot</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <span class="badge bg-{{ $log->action === 'created' ? 'success' : 'warning' }}">
                            {{ ucfirst($log->action) }}
                        </span>
                    </td>
                    <td>
                        {{ $log?->user?->name ?? 'System' }}
                    </td>
                    <td>
                        {{ $log->logged_at }}
                    </td>
                    <td>
                        <details>
                            <summary class="text-primary cursor-pointer">View</summary>
                            <pre class="mt-2">{{ json_encode($log->student_snapshot, JSON_PRETTY_PRINT) }}</pre>
                        </details>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
<a href="{{ route('students.index') }}" class="btn btn-secondary mb-3">
    <i class="fa fa-arrow-left"></i> Back to Students
</a>
</div>
@endsection
@section('page-js')
    <script>
        $(document).ready(function() {
            new simpleDatatables.DataTable("#studentLogs", {
                searchable: true,
                perPage: 10
            });

        });
    </script>
@endsection
