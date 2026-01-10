@extends('admin.admin_main')

@section('page-css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="container-fluid">
    @if(session('msg'))
        <div class="alert alert-success">
            {{ session('msg') }}
        </div>
    @endif
        @include('admin.students.student_form', [
            'is_update' => true,
        ])

    </div>
@endsection

@section('page-js')

@endsection
