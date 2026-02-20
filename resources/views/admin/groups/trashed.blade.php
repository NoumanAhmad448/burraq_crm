@extends('admin.admin_main')

@section('content')
    <div class="container">
        <h3>Trashed Groups</h3>
        <a href="{{ route('admin.groups.index') }}" class="btn btn-secondary mb-2">Back to Active Groups</a>

        <table id="crm_groups_trashed" class="table table-bordered">
            <thead>
                <tr>
                    <th>Group Name</th>
                    <th>Timing</th>
                    <th>Deleted At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($groups as $group)
                    <tr>
                        <td>{{ $group->group_name }}</td>
                        <td>{{ $group->timing }}</td>
                        <td>{{ $group->deleted_at }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.groups.restore', $group->id) }}"
                                style="display:inline-block;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success btn-sm">Restore</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('page-js')
    <script>
        $(document).ready(function() {
            new simpleDatatables.DataTable("#crm_groups_trashed", {
                searchable: true,
                perPage: 10
            });
        });
    </script>
@endsection
