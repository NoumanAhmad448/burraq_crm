<a href="{{ route('students.index') }}" class="btn btn-secondary my-3">
    <i class="fa fa-arrow-left"></i> Back to Students
</a>
@notempty($enrolledCourse && $student_id)
<a href="{{ route('students.course.payments', ['student_id' => $student_id, 'enrolledCourseId' => $enrolledCourse]) }}"
    class="btn btn-success my-3">
    <i class="fa fa-arrow-left"></i> Back to Payments
</a>
@endnotempty