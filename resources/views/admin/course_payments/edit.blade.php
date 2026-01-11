@extends('admin.admin_main')

@section('page-css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="container">


        @include('admin.course_payments.payment_form', [
            'is_update' => true,
            'enrolledCourse' => $enrolledCourse,
            'student_id' => $student_id,
            'enrolled_course_id' => $enrolled_course_id,
            "payment" => $payment,
        ])

    </div>
@endsection
