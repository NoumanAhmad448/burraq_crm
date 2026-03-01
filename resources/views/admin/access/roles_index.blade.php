@extends('admin.admin_main')

@section('content')

<div class="card">
    <div class="card-header">
        <h4>Role Management</h4>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">
            <i class="fa fa-plus"></i> Add Role
        </a>
    </div>

    <div class="card-body">

        @include('export_to_excel')

        <table id="crm_roles" class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Role Name</th>
                    <th>Total Permissions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ ucfirst($role->name) }}</td>
                    <td>{{ $role->permissions_count }}</td>
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
    new simpleDatatables.DataTable("#crm_roles", {
        searchable: true,
        perPage: 10
    });
});
</script>
@endsection