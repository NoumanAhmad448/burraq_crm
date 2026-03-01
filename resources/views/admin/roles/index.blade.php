@extends('admin.admin_main')

@section('content')

<div class="card">
    <div class="card-header">
        <h4>Roles</h4>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">
            <i class="fa fa-plus"></i> Create Role
        </a>
    </div>

    <div class="card-body">
        <table id="crm_roles" class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Permissions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ ucfirst($role->name) }}</td>
                        <td>
                            @foreach($role->permissions as $permission)
                                <span class="badge badge-info">
                                    {{ $permission->name }} , 
                                </span>
                            @endforeach
                        </td>
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