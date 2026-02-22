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
            @include("components.student-filters")
            <div class="card-body">
                <table class="table table-bordered crm_students" id="crm_students">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Mobile No</th>
                            <th>Father Name</th>
                            <th>Total Fee</th>
                            <th>Paid Fee</th>
                            <th>Remaining Fee</th>
                            <th>Status</th>
                            <th>Courses(Payments)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($enrolledCourses as $course)
                            <tr @if($course->student->is_deleted == 1) class="table-danger" title="Student Deleted"
                                @elseif(\App\Models\Certificate::where('student_id', $course->student->id)->where('enrolled_course_id', $course->id)->exists()) class="table-success" title="Certificate Issued"
                                @endif>
                                <td>{{ $course->student->name }}</td>
                                <td>{{ $course->student->mobile }}</td>
                                <td>{{ $course->student->father_name }}</td>
                                <td>{{ show_payment($course?->total_fee) }}</td>
                                @php
                                    $paid_payment = $course?->payments()?->totalPaid();
                                @endphp
                                <td>
                                    {{ show_payment($paid_payment) }}
                                </td>
                                <td>
                                    {{ show_payment($course->total_fee - $paid_payment) }}
                                </td>
                                @include("payment_status")
                                <td>
                                    @if($course)
                                        <a href="{{ route('students.course.payments', ['student_id' => $course->student->id, 'enrolledCourseId' => $course->id]) }}"
                                            class="underscore text-primary">
                                            {{ \Str::limit($course->course->name, 30) }} <br/>
                                        </a>
                                    @endif
                                </td>
                                @include('admin.students.student_action')

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
