    {{-- ================= CREATE STUDENT FORM ================= --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong>
                @if ($is_update)
                    Edit Student
                @else
                    Create Student
                @endif
            </strong>
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
            <form method="POST"
                action="@if ($is_update) {{ route('students.update', $student->id) }}@else{{ route('students.store') }} @endif"
                enctype="multipart/form-data">
                @csrf
                @method('post')
                @if($is_update && $student?->photo)
                    <div class="row justify-content-center align-items-center mb-4">
                        <img src="{{ asset(img_path($student?->photo)) }}" alt="lyskills" width="100" height="100"
                        class="img-fluid mb-1 rounded-circle shadow-sm img-fluid w-25 h-25" />
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-4">
                        <label>Name *</label>
                        <input type="text" name="name" class="form-control" required
                            value="@if ($is_update) {{ $student->name }}@else{{ old('name') }} @endif">
                    </div>

                    <div class="col-md-4">
                        <label>Father Name *</label>
                        <input type="text" name="father_name" class="form-control" required
                            value="@if ($is_update) {{ $student->father_name }}@else{{ old('father_name') }} @endif">
                    </div>

                    <div class="col-md-4">
                        <label>CNIC *</label>
                        <input type="text" name="cnic" class="form-control"
                            value="@if ($is_update) {{ $student->cnic }}@else{{ old('cnic') }} @endif">
                    </div>

                    <div class="col-md-4 mt-2">
                        <label>Mobile *</label>
                        <input type="text" name="mobile" class="form-control"
                            value="@if ($is_update) {{ $student->mobile }}@else{{ old('mobile') }} @endif">
                    </div>

                    <div class="col-md-4 mt-2">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control"
                            value="@if ($is_update) {{ $student->email }}@else{{ old('email') }} @endif">
                    </div>

                    <div class="col-md-4 mt-2">
                        <label>Photo</label>
                        <input type="file" name="photo" class="form-control"
                            value="{{ old('photo') }}">
                    </div>

                    <div class="col-md-4 mt-2">
                        <label>Admission Date*</label>
                        <input type="text" name="admission_date" class="form-control datepicker"
                            value="@if ($is_update) {{ $student?->admission_date }}@else{{ old('admission_date') }} @endif">
                    </div>
                    <div class="col-md-4 mt-2">
                        <label>Due Date *</label>
                        <input type="text" name="due_date" class="form-control datepicker"
                            value="@if ($is_update) {{ dateFormat($student->admission_date) }}@else{{ old('due_date') }} @endif">
                    </div>

                    <div class="col-md-4 mt-2">
                        <label>Total Fee *</label>
                        <input type="text" name="total_fee" class="form-control" required        step="any"
                            value="@if ($is_update) {{ (int) $student->total_fee }}@else{{ old('total_fee') }} @endif">
                    </div>
                    <div class="col-md-4 mt-2">
                        <label>Paid Fee *</label>
                        <input type="text" name="paid_fee" class="form-control" required        step="0.01"

                            value="@if ($is_update) {{ (int) $student->paid_fee }}@else{{ old('paid_fee') }} @endif">
                    </div>

                </div>

                {{-- ================= COURSES ================= --}}
                <hr>
                <strong>Enroll Courses</strong>

                @foreach ($courses as $course)
                    <div class="row mt-2">
                        <div class="col-md-1">
                            <input type="checkbox" name="courses[{{ $course->id }}][selected]"
                            @if($is_update && $student?->enrolledCourses->contains('course_id', $course->id)) checked @endif
                            >
                            @if($is_update)
                                <input type="hidden" name="courses[{{ $course->id }}][CEId]"
                                @if($student?->enrolledCourses->contains('course_id', $course->id))
                                value="{{ $student?->enrolledCourses->where('course_id', $course->id)->first()->id }}" @endif
                            >
                            @endif
                        </div>

                        <div class="col-md-5">
                            {{ $course->name }} @if ($course->is_deleted)
                                                            <span class="badge badge-danger ml-2">Deleted</span>
                                                        @endif

                        </div>

                        <div class="col-md-3">
                            <input type="text" name="courses[{{ $course->id }}][total_fee]"
                                placeholder="Course Fee" class="form-control"
                                value="@if($is_update && $student?->enrolledCourses->contains('course_id', $course->id)) {{ $student?->enrolledCourses->where('course_id', $course->id)->first()->total_fee }}@else{{ old('total_fee') }}@endif">
                        </div>

                        <div class="col-md-3">
                            @php
                            if($is_update){
                                $enrolledCourses = $student?->enrolledCourses->where('course_id', $course->id)->where("student_id", $student?->id)->first();
                            }
                            //  dump($enrolledCourses?->id);
                            @endphp
                            @if($is_update)
                                <input type="hidden" name="courses[{{ $course->id }}][payId]"
                                @if($is_update && $enrolledCourses)
                                value="{{ $enrolledCourses?->payments?->first()?->id }}" @endif
                            >
                            @endif
                            <input type="text" name="courses[{{ $course->id }}][paid_amount]" placeholder="Paid"
                                class="form-control" @if($is_update && $enrolledCourses)
                                value="{{ (int) $enrolledCourses?->payments?->first()?->paid_amount }}" @else value="{{ old('paid_amount') }}@endif">
                        </div>
                    </div>
                @endforeach

                {{-- ================= CHECKBOX OPTIONS ================= --}}
                <hr>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="print" value="1">
                    <label class="form-check-label">Print Student</label>
                </div>
                @if ($is_update == false)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="continue_add" value="1" checked>
                        <label class="form-check-label">Continue Add</label>
                    </div>
                @else
                        <div class="d-flex justify-content-end mb-3">
                            <a href="{{ route('students.print', $student->id) }}"
                            class="btn btn-secondary">
                                Print Student
                            </a>
                        </div>
                @endif

                <button class="btn btn-primary mt-3">
                    @if ($is_update)
                        Update Student
                    @else
                        Save Student
                    @endif
                </button>
            </form>
        </div>
    </div>
