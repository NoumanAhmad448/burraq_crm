@extends('admin.admin_main')

@section('page-css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="container-fluid">

        @include('admin.students.student_form', [
            'is_update' => false,
        ])

        {{-- ================= STUDENT LIST ================= --}}
        <div class="card">
            <div class="card-header">
                <strong>Students List</strong>
            </div>

            <div class="card-body">
                <table class="table table-bordered crm_students" id="crm_students">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Father Name</th>
                            <th>Total Fee</th>
                            <th>Payed Fee</th>
                            <th>Remaining Fee</th>
                            <th>Courses(Payments)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        {{-- {{dd($students)}} --}}
                        @foreach ($students as $student)
                            <tr @if($student->is_deleted == 1) class="table-danger" @endif>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->father_name }}</td>
                                <td>{{ (int)$student->total_fee }}</td>
                                <td>{{ (int) $student->paid_fee }}</td>
                                <td>
                                    {{ (int) $student->remaining_fee }}
                                </td>
                                <td>
                                @if($student->enrolledCourses->isNotEmpty() && $student->enrolledCourses->first())
                                    @foreach ($student->enrolledCourses as $id => $enrolled)
                                        <a href="{{ route('students.course.payments', ['student_id' => $student->id, 'enrolledCourseId' => $enrolled->id]) }}"
                                            class="underscore text-primary">
                                            {{$id + 1}} - {{ \Str::limit($enrolled->course->name, 30) }} <br/>
                                        </a>
                                    @endforeach
                                @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1 justify-content-center justify-between">
                                        <a href="{{ route('students.edit', $student->id) }}"
                                        class="btn btn-sm btn-info"
                                        title="Edit">
                                            <i class="fa fa-pencil"></i> Edit
                                        </a>

                                        @if(isset($enrolled))
                                            <a href="{{ $enrolled ? route('students.course.payments', ['student_id' => $student->id, 'enrolledCourseId' => $enrolled->id]) : '#' }}"
                                                class="btn btn-sm btn-warning {{ !$enrolled ? 'disabled' : '' }}"
                                                title="Course -> Payments"
                                                @if(!$enrolled) onclick="return false;" @endif>
                                                    <i class="fa fa-credit-card"></i> Payments
                                            </a>
                                        @endif
                                        @if (auth()->user()->is_admin)
                                            <a href="{{ route('students.logs', $student->id) }}"
                                            class="btn btn-sm btn-primary"
                                            title="View Logs">
                                                <i class="fa fa-history"></i> Student Logs
                                            </a>

                                            <a href="{{ route('students.course.payments_logs', $student->id) }}"
                                            class="ml-3 mt-2 btn btn-sm btn-secondary"
                                            title="Course & Payments Logs"> Payments Logs
                                                <i class="fa fa-credit-card"></i>
                                            </a>

                                            <a href="{{ route('students.delete', $student->id) }}"
                                            class="ml-3 mt-2 btn btn-sm btn-danger"
                                            title="Delete"
                                            onclick="return confirm('Are you sure?')">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        @endif
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
    <script>
        function loadStudentDetail(id) {
            showLoader();
            $.get('/students/' + id, function(res) {
                hideLoader();
                $('#studentDetailModal').html(res).modal('show');
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            new simpleDatatables.DataTable("#crm_students", {
                searchable: true,
                perPage: 10
            });

        });
    </script>
@endsection
