<td>
    <div class="d-flex flex-wrap gap-2 justify-content-center">
        <a href="{{ route('students.edit', $course->student->id) }}" class="btn btn-sm btn-info"
            title="Edit the Student and his course info">
            <i class="fa fa-pencil"></i>
        </a>

        @if (isset($course))
            <a href="{{ $course ? route('students.course.payments', ['student_id' => $course->student->id, 'enrolledCourseId' => $course->id]) : '#' }}"
                class="btn btn-sm btn-warning ml-1 {{ !$course ? 'disabled' : '' }}" title="All Course Payments"
                @if (!$course) onclick="return false;" @endif>
                <i class="fa fa-credit-card"></i>
            </a>
        @endif

        <x-admin>
            <a href="{{ route('students.logs', $course->student->id) }}" class="btn btn-sm btn-primary mt-1 ml-1"
                title="View Student Logs">
                <i class="fa fa-history"></i>
            </a>

            <a href="{{ route('students.course.payments_logs', $course->student->id) }}"
                class="btn btn-sm btn-secondary mt-1 ml-1" title="Payments Logs of the course">
                <i class="fa fa-credit-card"></i>
            </a>
            <a href="{{ route('enrolled-courses.status.edit', $course->id) }}"
                class="btn btn-sm btn-secondary mt-1 ml-1" title="Course Enrollement">
                <i class="fa fa-credit-card"></i>
            </a>

            <x-delete :route="route('students.delete', $course->student->id)" title="Delete the student permanently" />
        </x-admin>
        <x-super-admin>
            @can('is-deleted-student', $course->student)
                <x-active :route="route('students.activate', $course->student->id)" />
            @endcan
        </x-super-admin>
    </div>
</td>
