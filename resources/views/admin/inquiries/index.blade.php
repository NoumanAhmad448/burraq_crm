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
@if(request('type'))
    <div class="alert alert-info">
        Showing inquiries for:
        <strong>{{ ucfirst(str_replace('_', ' ', request('type'))) }}</strong>
    </div>
@endif

            <div class="row mb-3 mt-3 mr-5">
            <div class="col-md-12 d-flex justify-content-end">

    <form method="GET" action="{{ route('inquiries.index') }}">
        <select name="type"
                class="form-control form-control-sm"
                onchange="this.form.submit()">

            <option value="all">All Active</option>

            <optgroup label="Status">
                <option value="pending" {{ request('type')=='pending' ? 'selected' : '' }}>
                    Pending
                </option>
                <option value="contacted" {{ request('type')=='contacted' ? 'selected' : '' }}>
                    Contacted
                </option>
                <option value="follow_up" {{ request('type')=='follow_up' ? 'selected' : '' }}>
                    Follow Up
                </option>
                <option value="not_interested" {{ request('type')=='not_interested' ? 'selected' : '' }}>
                    Not Interested
                </option>
                <option value="not_contacted" {{ request('type')=='not_contacted' ? 'selected' : '' }}>
                    Not Contacted
                </option>
            </optgroup>

            <optgroup label="This Month">
                <option value="this_month_pending"
                    {{ request('type')=='this_month_pending' ? 'selected' : '' }}>
                    Pending (This Month)
                </option>

                <option value="this_month_contacted"
                    {{ request('type')=='this_month_contacted' ? 'selected' : '' }}>
                    Contacted (This Month)
                </option>
            </optgroup>

        </select>
    </form>
</div>
            </div>

<table class="table table-bordered databelle mt-4">
    <thead>
        <tr>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Course</th>
            <th>Source</th>
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