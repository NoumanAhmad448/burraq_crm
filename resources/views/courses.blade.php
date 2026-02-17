@empty($design)
<div class="col-md-3">
@endif
    {{-- <label>Select Course</label> --}}
    <select name="course_id" class="form-control select">
        <option value="">-- All Courses --</option>
        @foreach ($courses as $course)
            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                {{ $course->name }}
            </option>
        @endforeach
    </select>
@empty ($design)
</div>
@endif
