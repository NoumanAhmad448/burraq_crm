@extends('admin.admin_main')


@section('content')
    <div class="container">
        @include('sc', [
            'enrolledCourse' => $enrolledCourse->id,
            'student_id' => $enrolledCourse->student->id,
        ])
        <div class="card shadow-sm">

            <div class="card-header">
                <h5 class="mb-0">Update Enrollment Status</h5>
            </div>

            <div class="card-body">
                @include('messages')

                <form method="POST" action="{{ route('enrolled-courses.status.update', $enrolledCourse->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Current Status</label>
                        <input type="text" class="form-control" value="{{ ucfirst($enrolledCourse->status) }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Status</label>
                        <select name="status" class="form-select">
                            <option value="dropped">Dropped</option>
                            <option value="refunded">Refunded</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Message (Required for Dropped)
                        </label>
                        <textarea name="status_note" class="form-control" rows="4" placeholder="Enter reason here...">{{ old('status_note', $enrolledCourse->status_note) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Update Status
                    </button>

                </form>

            </div>

        </div>
        @include('sc', [
            'enrolledCourse' => $enrolledCourse->id,
            'student_id' => $enrolledCourse->student->id,
        ])
    </div>
@endsection
