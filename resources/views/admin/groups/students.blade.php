@extends('admin.admin_main')

@section('content')
    <div class="container">
        <a class="btn btn-lg btn-success" href="{{ route("admin.groups.index") }}">Back To Group Module</a>
        <a class="btn btn-lg btn-secondary" href="{{ route("admin.group.modules", $group) }}">Back To Modules</a>
        
        @include('admin.groups.ass_stu')
        <hr>

        <h3>Group Name - {{ $group->group_name }}</h3>

        @include('messages')
        <table id="group_students_table" class="table table-bordered">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Student Name</th>
                    <th>Phone</th>
                    <th>Course</th>
                    <th>Action</th>
                </tr>
            </thead>
            {{-- @dd($group->enrolledCourses) --}}
            <tbody>
                @foreach ($group->enrolledCourses as $enrolled_course)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $enrolled_course->student->name ?? 'N/A' }}</td>
                        <td>{{ $enrolled_course->student->mobile ?? '-' }}</td>
                        <td>{{ $enrolled_course->course->name }}</td>
                        <td>
                            {{-- @dd("here") --}}
                            <x-admin>
                                <form
                                    action="{{ route('admin.groups.students.destroy', [$group->id, $enrolled_course->id]) }}"
                                    method="POST" style="display:inline;"
                                    onsubmit="return confirm('Are you sure you want to remove this student from the group?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        Remove
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
@include("export_to_excel", ["id"=>"#group_students_table"
])
@endsection
