@extends('admin.admin_main')

@section('page-css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')


@include('admin.inquiries.inquiry_form', [
    'is_update' => false,
    'courses' => $courses,
])

{{-- ================= INQUIRY LIST ================= --}}

<table class="table table-bordered databelle mt-4">
    <thead>
        <tr>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Course</th>
            <th>Status</th>
            <th>Deleted</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($inquiries as $inq)
        <tr class="{{ $inq->deleted_at ? 'table-danger' : '' }}">
            <td>{{ $inq->name }}</td>
            <td>{{ $inq->phone }}</td>
            <td>{{ $inq->email }}</td>
            <td>{{ ucfirst($inq?->course?->name) }}</td>
            <td>{{ ucfirst($inq->status) }}</td>
            <td>{{ $inq->deleted_at ? 'Yes' : 'No' }}</td>
            <td>
                <a href="{{ route('inquiries.edit',$inq->id) }}" class="btn btn-sm btn-primary">Edit</a>
                @if(auth()->user()->is_admin)
                <a href="{{ route('inquiries.logs', $inq->id) }}"
   class="btn btn-sm btn-info">
    Logs
</a>

                @if(!$inq->deleted_at)
                <form method="POST" action="{{ route('inquiries.delete',$inq->id) }}" style="display:inline;">
                    @csrf
                    <button class="btn btn-sm btn-danger">Delete</button>
                </form>
                @endif
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
@section('page-js')
<script>
const INQUIRY_STATUS = ['pending','resolved','other'];
</script>

<script>
function showLoader() {
    $('#loader').show();
}

function hideLoader() {
    $('#loader').hide();
}

new simpleDatatables.DataTable(".databelle", {
                searchable: true,
                perPage: 10
            });
</script>

@endsection