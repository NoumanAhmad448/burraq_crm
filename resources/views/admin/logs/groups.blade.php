@extends('admin.admin_main')

@section('content')

<table class="table table-bordered table-striped" id="group_students_table">
    <thead>
        <tr>
            <th>#</th>
            <th>User</th>
            <th>Action</th>
            <th>Old Values</th>
            <th>New Values</th>
            <th>Date</th>
        </tr>
    </thead>

    <tbody>
        @forelse($logs as $log)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $log->user->name }}</td>
                <td>{{ ucfirst($log->action) }}</td>

                <td>
                    @if($log->old_values)
                        <ul>
                            @foreach($log->old_values as $key => $value)
                                <li><strong>{{ $key }}</strong>: {{ $value }}</li>
                            @endforeach
                        </ul>
                    @endif
                </td>

                <td>
                    @if($log->new_values)
                        <ul>
                            @foreach($log->new_values as $key => $value)
                                <li><strong>{{ $key }}</strong>: {{ $value }}</li>
                            @endforeach
                        </ul>
                    @endif
                </td>

                <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">No logs found</td>
            </tr>
        @endforelse
    </tbody>
</table>


@endsection

@section('page-js')
@include("export_to_excel", ["id"=>"#group_students_table"
])
@endsection
