@extends('admin.admin_main')

@section('page-css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            HR Logs
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="crm_hr_logs">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Action</th>
                        <th>Performed By</th>
                        <th>Model</th>
                        <th>Record ID</th>
                        <th>Old Values</th>
                        <th>New Values</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @if($logs)
                    @foreach($logs as $log)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->performed_by }}</td>
                        <td>{{ $log->model }}</td>
                        <td>{{ $log->record_id }}</td>
                        <td><pre>{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre></td>
                        <td><pre>{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre></td>
                        <td>{{ $log->created_at }}</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('page-js')
<script>
    $(document).ready(function() {
        new simpleDatatables.DataTable("#crm_hr_logs", {
            searchable: true,
            perPage: 10
        });
    });
</script>
@endsection
