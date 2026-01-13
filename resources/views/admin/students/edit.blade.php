@extends('admin.admin_main')

@section('page-css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="container-fluid">
        <a href="{{ route('students.index') }}" class="btn btn-secondary mb-3">
    <i class="fa fa-arrow-left"></i> Back to Students
</a>
    @if(session('msg'))
        <div class="alert alert-success">
            {{ session('msg') }}
        </div>
    @endif
        @include('admin.students.student_form', [
            'is_update' => true,
        ])
<a href="{{ route('students.index') }}" class="btn btn-secondary mb-3">
    <i class="fa fa-arrow-left"></i> Back to Students
</a>
    </div>
@endsection

@section('page-js')

@endsection
