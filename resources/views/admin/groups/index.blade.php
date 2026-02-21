@extends('admin.admin_main')

@section('page-css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="container">
        <h2>Assign Instructors</h2>

        @include("admin.groups.ins_form")
        <hr>

        <!-- Assign Student Form -->
        @include("admin.groups.ass_stu")
        <hr>
        <div class="d-flex justify-content-between">
            <h3>Groups</h3>
            <a href="{{ route('admin.groups.create') }}" class="btn btn-success mb-2">Create Group</a>
        </div>

        <!-- Group Table -->
        <table id="crm_groups" class="table table-bordered">
            <thead>
                <tr>
                    <th>Group Name</th>
                    <th>Timing</th>
                    <th>Instructors</th>
                    <th>Status (%)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($groups as $group)
                    <tr>
                        <td>{{ $group->group_name }}</td>
                        <td>{{ $group->timing }}</td>
                        <td>
                            @foreach ($group->instructors as $instr)
                                {{ $instr->name }}@if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        </td>
                        <td>{{ $group->status }}</td>
                        <td class="text-center">
                        <div class="d-flex justify-content-center gap-4 flex-wrap"          style="gap: 3px;">

                            <a href="{{ route('admin.group.modules', $group->id) }}" class="btn btn-sm btn-primary">
                                Manage Modules
                            </a>
                            <x-admin>
                                <a href="{{ route('admin.groups.edit', $group->id) }}"
                                    class="btn btn-primary btn-sm ml-2">Edit</a>
                                <a href="{{ route('admin.group.students', $group->id) }}"
                                    class="btn btn-primary btn-sm ml-2">Group Students</a>
                                <a href="{{ route('admin.logs.groups', $group->id) }}"
                                    class="btn btn-primary btn-sm ml-2">Group Logs</a>
                                {{-- <a href="{{ route('admin.logs.group_enrollments', $group->id) }}"
                                    class="btn btn-primary btn-sm ml-2">Group Students</a> --}}

                                <form method="POST" action="{{ route('admin.groups.destroy', $group->id) }}"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm ml-2"
                                        onclick="return confirm('Are you sure you want to delete this group?')">
                                        Delete
                                    </button>
                                </form>

                            </x-admin>
                        </div>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('page-js')
        @include("export_to_excel", ["id"=>"#crm_groups"
])

@endsection
