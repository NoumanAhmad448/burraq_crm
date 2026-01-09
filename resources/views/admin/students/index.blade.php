@extends('admin.admin_main')

@section('page-css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')

<div class="container-fluid">

    {{-- ================= CREATE STUDENT FORM ================= --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong>Create Student</strong>
        </div>
        @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

        <div class="card-body">
            <form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data">
                @csrf
                @method("post")

                <div class="row">
                    <div class="col-md-4">
                        <label>Name *</label>
                        <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                    </div>

                    <div class="col-md-4">
                        <label>Father Name *</label>
                        <input type="text" name="father_name" class="form-control" required value="{{ old('father_name') }}">
                    </div>

                    <div class="col-md-4">
                        <label>CNIC *</label>
                        <input type="text" name="cnic" class="form-control" value="{{ old('cnic') }}">
                    </div>

                    <div class="col-md-4 mt-2">
                        <label>Mobile *</label>
                        <input type="text" name="mobile" class="form-control" value="{{ old('mobile') }}">
                    </div>

                    <div class="col-md-4 mt-2">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>

                    <div class="col-md-4 mt-2">
                        <label>Photo</label>
                        <input type="file" name="photo" class="form-control" value="{{ old('photo') }}">
                    </div>

                    <div class="col-md-4 mt-2">
                        <label>Admission Date*</label>
                        <input type="date" name="admission_date" class="form-control" value="{{ old('admission_date') }}">
                    </div>

                    <div class="col-md-4 mt-2">
                        <label>Due Date *</label>
                        <input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}">
                    </div>

                    {{-- <div class="col-md-4 mt-2">
                        <label>Role</label>
                        <select name="role" class="form-control" value="{{ old('total_fee') }}">
                            <option value="employee">Employee</option>
                            <option value="hr">HR</option>
                        </select>
                    </div> --}}

                    <div class="col-md-4 mt-2">
                        <label>Total Fee *</label>
                        <input type="number" name="total_fee" class="form-control" required value="{{ old('total_fee') }}">
                    </div>

                    <div class="col-md-4 mt-2">
                        <label>Paid Fee *</label>
                        <input type="number" name="paid_fee" class="form-control" required        value="{{ old('paid_fee') }}"
>
                    </div>

                </div>

                {{-- ================= COURSES ================= --}}
                <hr>
                <strong>Enroll Courses</strong>

                @foreach($courses as $course)
                    <div class="row mt-2">
                        <div class="col-md-1">
                            <input type="checkbox" name="courses[{{ $course->id }}][selected]">
                        </div>

                        <div class="col-md-5">
                            {{ $course->name }}
                        </div>

                        <div class="col-md-3">
                            <input type="number"
                                   name="courses[{{ $course->id }}][total_fee]"
                                   placeholder="Course Fee"
                                   class="form-control" value="{{ old('total_fee') }}">
                        </div>

                        <div class="col-md-3">
                            <input type="number"
                                   name="courses[{{ $course->id }}][paid_amount]"
                                   placeholder="Paid"
                                   class="form-control" value="{{ old('total_fee') }}">
                        </div>
                    </div>
                @endforeach

                {{-- ================= CHECKBOX OPTIONS ================= --}}
                <hr>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="print" value="1">
                    <label class="form-check-label">Print Student</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="receipt" value="1">
                    <label class="form-check-label">Download Receipt</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="continue_add" value="1" checked>
                    <label class="form-check-label">Continue Add</label>
                </div>

                <button class="btn btn-primary mt-3">Save Student</button>
            </form>
        </div>
    </div>

    {{-- ================= STUDENT LIST ================= --}}
    <div class="card">
        <div class="card-header">
            <strong>Students List</strong>
        </div>

        <div class="card-body">
            <table class="table table-bordered crm_students">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Father Name</th>
                        <th>Courses</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($students as $student)
                    <tr>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->father_name }}</td>
                        <td>

                        @foreach($student->enrolledCourses as $enrolled)
                            <a href="{{ route('student.course.detail', $enrolled->id) }}">
                                {{ \Str::limit($enrolled->course->name, 30) }}
                            </a><br>
                        @endforeach

                        </td>
                        <td>
                            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-info">
                                Edit
                            </a>

                            @if(auth()->user()->is_admin)
                                <a href="{{ route('students.delete', $student->id) }}"
                                   class="btn btn-sm btn-danger">
                                    Delete
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@section('page-js')
<script>
function loadStudentDetail(id) {
    showLoader();
    $.get('/students/' + id, function (res) {
        hideLoader();
        $('#studentDetailModal').html(res).modal('show');
    });
}
</script>
@endsection
