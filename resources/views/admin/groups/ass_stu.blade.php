<!-- Assign Student Form -->
<h2 class="mt-2">Assign Student to Group</h2>
<form method="POST" action="{{ route('admin.group.assign_student') }}" class="form-group">
    @csrf
    <div class="form-group">
        @include('admin.groups.group_listing')
    </div>
    {{-- @dd(App\Models\EnrolledCourse::activeCourse()->with("student", "course")->get()) --}}
    <div class="form-group">
        <label for="enrolled_course_id">Choose Student <small class="text-danger"> Listing format: name -- email -- phone
                -- course-name</small></label>
        <select name="enrolled_course_id" class="form-control select">
            <option value=""> -- Select Student -- </option>

            @foreach (App\Models\EnrolledCourse::with('activeStudent', 'course')->activeStudentInRelation()->activeCourse()->get() as $enrolled)
                <option value="{{ $enrolled->id }}">
                    @include('admin.groups.listed_stu')
                </option>
            @endforeach
        </select>
    </div>
    <button class="btn btn-primary mt-2"> <i class="fa fa-save"></i> Assign</button>
</form>
