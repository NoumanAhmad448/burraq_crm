@extends('admin.admin_main')

@section('page-css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
@if(!empty($is_update) && $is_update == false)
@include("user.form", ["is_update" => $is_update ?? false , "user" => $user ?? ""])
@endif
@if(!(!empty($is_update)))
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3>{{ $type === 'deleted' ? 'Deleted Users' : 'Users' }}</h3>

    </div>

    <div class="card-body">
        <table id="crm_users" class="table table-bordered table-striped w-100">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Admin</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>

                    {{-- Show role only if not admin --}}
                    <td>
                        @if(!$user->is_admin)
                            {{ $user->role }}
                        @else
                            -
                        @endif
                    </td>

                    {{-- Admin column --}}
                    <td>{{ $user->is_admin ? 'Yes' : 'No' }}</td>

                    <td>
                        @if(auth()->user()->is_admin && !empty($user))
                        <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-primary btn-sm">Edit</a>

                        <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Delete user?')">Delete</button>
                        </form>
                        <a href="{{ route('hr.logs', ["user_id" => $user?->id]) }}" class="btn btn-info btn-sm">Logs</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
@endif
@endsection


@section('page-js')
@if(!(!empty($is_update)))

<script>
$(document).ready(function() {
    new simpleDatatables.DataTable("#crm_users", {
        searchable: true,
        perPage: 10
    });
});
</script>
@endif
<script>
$(document).ready(function() {
    // Show loader on submit
    $('form').on('submit', function() {
        showLoader(); // your profile.js loader function
    });
});
</script>

@endsection
