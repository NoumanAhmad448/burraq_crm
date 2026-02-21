<!-- Assign Student Form -->
<h2 class="mt-2">Assign Student to Group</h2>
<form method="POST" action="{{ route('admin.group.assign_student', $group->id ?? 0) }}">
    @csrf
    @include('admin.groups.group_listing')
    {{-- @dd(App\Models\EnrolledCourse::activeCourse()->with("student", "course")->get()) --}}
    <label for="enrolled_course_id">Choose Student</label>
    <select name="enrolled_course_id" class="form-control select">
        @foreach (App\Models\EnrolledCourse::with('student', 'course')->get() as $enrolled)
            <option value="{{ $enrolled->id }}">
                @include('admin.groups.listed_stu')
            </option>
        @endforeach
    </select>
    <button class="btn btn-primary mt-2">Assign</button>
</form>
