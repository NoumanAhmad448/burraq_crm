@extends('admin.admin_main')

@section('content')
<div class="container-fluid">

    <h4>Add Payment</h4>

    <table class="table table-bordered mb-4">
        <tr>
            <th>Student</th>
            <td>{{ $enrolledCourse->student->name }}</td>
        </tr>
        <tr>
            <th>Course</th>
            <td>{{ $enrolledCourse->course->name }}</td>
        </tr>
        <tr>
            <th>Total Fee</th>
            <td>{{ $enrolledCourse->course->fee }}</td>
        </tr>
        <tr>
            <th>Already Paid</th>
            <td>{{ $totalPaid }}</td>
        </tr>
        <tr>
            <th>Remaining</th>
            <td>{{ $enrolledCourse->course->fee - $totalPaid }}</td>
        </tr>
    </table>

    <form method="POST" action="{{ route('enrolled.course.payment.store') }}">
        @csrf

        <input type="hidden" name="enrolled_course_id" value="{{ $enrolledCourse->id }}">

        <div class="form-group">
            <label>Payment Amount</label>
            <input type="number" name="amount" class="form-control" required>
            @error('amount') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Paid Date</label>
            <input type="date" name="paid_at" class="form-control" required>
        </div>

        <button class="btn btn-primary">Save Payment</button>
        <a href="{{ route('students.course.detail', [$enrolledCourse->student_id, $enrolledCourse->id]) }}"
           class="btn btn-secondary">
            Cancel
        </a>
    </form>

</div>
@endsection
