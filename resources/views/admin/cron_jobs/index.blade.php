@extends('admin.admin_main')

@section('page-css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')

<div class="container-fluid">
    <h4 class="mb-4">Cron Jobs Log</h4>

    <table class="table table-bordered" id="crm_cron_jobs">
        <thead class="thead-dark">
            <tr>
                <th>Name</th>
                <th>Worker Name</th>
                <th>Status</th>
                <th>Message</th>
                <th>Starts At</th>
                <th>Ends At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cronJobs as $job)
                <tr>
                    <td>{{ $job->name }}</td>
                    <td>{{ $job->w_name }}</td>
                    <td>
                        @if($job->status === 'success')
                            <span class="badge badge-success">Success</span>
                        @else
                            <span class="badge badge-danger">Failed</span>
                        @endif
                    </td>
                    <td>{{ $job->message }}</td>
                    <td>{{ \App\Classes\LyskillsCarbon::parse($job->starts_at)->format('d M Y H:i:s') }}</td>
                    <td>{{ $job->ends_at ? \App\Classes\LyskillsCarbon::parse($job->ends_at)->format('d M Y H:i:s') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection

@section('page-js')
<script>
    $(document).ready(function() {
        new simpleDatatables.DataTable("#crm_cron_jobs", {
            searchable: true,
            perPage: 10
        });
    });
</script>
@endsection
