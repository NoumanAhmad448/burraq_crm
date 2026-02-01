@extends('admin.admin_main')

@section('page-css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="container-fluid">

        @if(request('type') !== 'deleted')
            @include('admin.students.student_form', [
                'is_update' => false,
            ])
        @endif

        {{-- ================= STUDENT LIST ================= --}}
        <div class="card">
            <div class="card-header">
                <strong>Students List</strong>
            </div>

        <x-student-filters :month="$month" :year="$year"/>
            <div class="card-body">
                <table class="table table-bordered crm_students" id="crm_students">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Mobile No</th>
                            {{-- <th>Cnic</th> --}}
                            <th>Father Name</th>
                            <th>Total Fee</th>
                            <th>Paid Fee</th>
                            <th>Remaining Fee</th>
                            {{-- <th>Admission Date</th> --}}
                            {{-- <th>Due Date</th> --}}
                            <th>Status</th>
                            <th>Courses(Payments)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        {{-- {{dd($enrolledCourses)}} --}}
                        @foreach ($enrolledCourses as $course)
                            <tr @if($course->student->is_deleted == 1) class="table-danger" title="Student Deleted"
                                @elseif(\App\Models\Certificate::where('student_id', $course->student->id)->where('enrolled_course_id', $course->id)->exists()) class="table-success" title="Certificate Issued"
                                @endif>
                                <td>{{ $course->student->name }}</td>
                                <td>{{ $course->student->mobile }}</td>
                                {{-- <td>{{ $course->student->cnic }}</td> --}}
                                <td>{{ $course->student->father_name }}</td>
                                <td>{{ show_payment($course?->total_fee) }}</td>
                                @php
                                $paid_payment = $course?->payments()?->where("is_deleted", 0)?->sum("paid_amount")
                                @endphp
                                <td>{{ show_payment($paid_payment) }}</td>
                                <td>
                                    {{ show_payment($course->total_fee - $paid_payment) }}
                                </td>
                                {{-- <td>{{ $course->admission_date ? dateFormat($course->admission_date) : 'N/A' }}</td> --}}
                                {{-- <td>{{ $course->due_date ? dateFormat($course->due_date) : 'N/A' }}</td> --}}
                                <td>
                                    <small @if($course->total_fee - $paid_payment <= 0) class="btn btn-success btn-rounded"
                                    @elseif(\Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($course->due_date)) && $course->total_fee - $paid_payment > 0) class="btn btn-danger btn-rounded"
                                    @elseif($paid_payment > 0 && $course->total_fee - $paid_payment > 0) class="btn btn-warning"
                                    @else class="btn btn-danger" @endif>

                                    @if($course->total_fee <= $paid_payment)
                                        Paid
                                    @elseif(\Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($course->due_date)) && $paid_payment < $course->total_fee)
                                        Overdue
                                    @elseif($paid_payment > 0 && $course->total_fee > $paid_payment)
                                        Unpaid
                                    @endif
                                    </small>
                                </td>
                                <td>
                                @if($course)
                                        <a href="{{ route('students.course.payments', ['student_id' => $course->student->id, 'enrolledCourseId' => $course->id]) }}"
                                            class="underscore text-primary">
                                            {{ \Str::limit($course->course->name, 30) }} <br/>
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                                        <a href="{{ route('students.edit', $course->student->id) }}"
                                        class="btn btn-sm btn-info"
                                        title="Edit the Student and his course info">
                                            <i class="fa fa-pencil"></i>
                                        </a>

                                        @if(isset($course))
                                            <a href="{{ $course ? route('students.course.payments', ['student_id' => $course->student->id, 'enrolledCourseId' => $course->id]) : '#' }}"
                                            class="btn btn-sm btn-warning ml-1 {{ !$course ? 'disabled' : '' }}"
                                            title="All Course Payments"
                                            @if(!$course) onclick="return false;" @endif>
                                                <i class="fa fa-credit-card"></i>
                                            </a>
                                        @endif

                                        <x-admin>
                                            <a href="{{ route('students.logs', $course->student->id) }}"
                                            class="btn btn-sm btn-primary mt-1 ml-1"
                                            title="View Student Logs">
                                                <i class="fa fa-history"></i>
                                            </a>

                                            <a href="{{ route('students.course.payments_logs', $course->student->id) }}"
                                            class="btn btn-sm btn-secondary mt-1 ml-1"
                                            title="Payments Logs of the course">
                                                <i class="fa fa-credit-card"></i>
                                            </a>

                                            <x-delete :route="route('students.delete', $course->student->id)"
                                                title="Delete the student permanently"/>
                                        </x-admin>
                                        <x-super-admin>
                                            @can("is-deleted-student", $course->student)
                                                <x-active :route="route('students.activate', $course->student->id)"
                                                    />
                                            @endcan
                                        </x-super-admin>
                                    </div>
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
@include("export_to_excel", ["id"=>"#crm_students"
])
@endsection
