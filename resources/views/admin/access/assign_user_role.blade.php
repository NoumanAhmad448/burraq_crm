@extends('admin.admin_main')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Assign Roles to Users</h4>
        </div>

        <div class="card-body">

            <table id="crm_users_roles" class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Assign Role & Permission</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>

                                <form method="POST" action="{{ route('admin.users.assign.roles.store') }}">
                                    @csrf

                                    <input type="hidden" name="user_id" value="{{ $user->id }}">

                                    {{-- Role --}}
                                    <div class="form-group">
                                        <select name="role" class="form-control mb-2" required>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}"
                                                    {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                                    {{ ucfirst($role->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Multiple Permissions --}}
                                    <div class="form-group">
                                        <select name="permissions[]" class="form-control" multiple>
                                            @foreach ($permissions as $permission)
                                                <option value="{{ $permission->name }}"
                                                    {{ $user->hasPermissionTo($permission->name) ? 'selected' : '' }}>
                                                    {{ $permission->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <button class="btn btn-primary btn-sm mt-2">
                                        <i class="fa fa-save"></i> Update
                                    </button>
                                </form>

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
            new simpleDatatables.DataTable("#crm_users_roles", {
                searchable: true,
                perPage: 10
            });
        });
    </script>
@endsection
