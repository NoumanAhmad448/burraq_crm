@extends('admin.admin_main')

@section('page-css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Inquiry Logs (ID: {{ $id }})</h5>
        </div>

        <div class="card-body">
            <table class="table table-bordered" id="crm_students">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Action</th>
                        <th>Old Data</th>
                        <th>New Data</th>
                        <th>Action By</th>
                        <th>IP</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $key => $log)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                @if ($log->action === 'resolved')
                                    <span class="badge badge-success">
                                        {{ ucfirst($log->action) }}
                                    </span>
                                @else
                                    <span class="badge badge-warning">
                                        {{ ucfirst($log->action) }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <pre>{{ json_encode($log->old_data, JSON_PRETTY_PRINT) }}</pre>
                            </td>
                            <td>
                                <pre>{{ json_encode($log->new_data, JSON_PRETTY_PRINT) }}</pre>
                            </td>
                            <td>{{ $log?->user?->name }}</td>
                            <td>{{ $log->ip_address }}</td>
                            <td>{{ $log->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('page-js')
    <script>
        $(document).ready(function() {
            $('#crm_students').DataTable();
        });
    </script>
@endsection
