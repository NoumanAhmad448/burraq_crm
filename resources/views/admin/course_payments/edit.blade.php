@extends('admin.admin_main')

@section('page-css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="container">
<a href="{{ route('students.index') }}" class="btn btn-secondary mb-3">
            <i class="fa fa-arrow-left"></i> Back to Students
        </a>
        <a href="{{ route('students.course.payments', ['student_id' => $enrolledCourse , 'enrolledCourseId' => $student_id]) }}" class="btn btn-success mb-3">
            <i class="fa fa-arrow-left"></i> Back to Payments
        </a>
    <div class="container">

        @include('admin.course_payments.payment_form', [
            'is_update' => true,
            'enrolledCourse' => $enrolledCourse,
            'student_id' => $student_id,
            'enrolled_course_id' => $enrolled_course_id,
            "payment" => $payment,
        ])

<a href="{{ route('students.index') }}" class="btn btn-secondary my-3" >
            <i class="fa fa-arrow-left"></i> Back to Students
        </a>
        <a href="{{ route('students.course.payments', ['student_id' => $enrolledCourse , 'enrolledCourseId' => $student_id]) }}" class="btn btn-success my-3">
            <i class="fa fa-arrow-left"></i> Back to Payments
        </a>

</div>

    @endsection
