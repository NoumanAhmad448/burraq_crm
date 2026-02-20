@extends('admin.admin_main')

@section('page-css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="container">
        <h3>Assign Instructors</h3>

        <!-- Assign Instructor Form -->
        <form method="POST" action="{{ route('admin.group.assign_instructor', $group->id ?? 0) }}">
            @csrf
            <label>Assign Instructors</label>
            <select name="instructors[]" multiple class="form-control select">
                @foreach (App\Models\User::where('role', config('settings.roles.instructor'))->get() as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} -- {{ $user->email }} </option>
                @endforeach
            </select>
            <button class="btn btn-success mt-2">Assign</button>
        </form>
        <hr>
        <h3>Assign Student to Group</h3>

        <!-- Assign Student Form -->
        <form method="POST" action="{{ route('admin.group.assign_student', $group->id ?? 0) }}">
            @csrf
            <label>Assign Student to Group</label>
            <select name="enrolled_course_id" class="form-control select">
                @foreach (App\Models\EnrolledCourse::all() as $enrolled)
                    <option value="{{ $enrolled->id }}">
                        {{ $enrolled->student->name }} -
                        {{ $enrolled->student->email }} -
                        {{ $enrolled->student->mobile }} -
                        {{ $enrolled->course->name ?? 'N/A' }}
                    </option>
                @endforeach
            </select>
            <button class="btn btn-primary mt-2">Assign</button>
        </form>
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
                        <td>
                            <a href="{{ route('admin.group.modules', $group->id) }}" class="btn btn-sm btn-primary">
                                Manage Modules
                            </a>
                            <x-admin>
                                <a href="{{ route('admin.groups.edit', $group->id) }}"
                                    class="btn btn-primary btn-sm">Edit</a>
                                <form method="POST" action="{{ route('admin.groups.destroy', $group->id) }}"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this group?')">
                                        Delete
                                    </button>
                                </form>
                            </x-admin>
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
            new simpleDatatables.DataTable("#crm_groups", {
                searchable: true,
                perPage: 10
            });
        });
    </script>
@endsection
