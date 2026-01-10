@extends('admin.admin_main')

@section('page-css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')


@include('admin.inquiries.inquiry_form', [
    'is_update' => true,
    'courses' => $courses,
])


@endsection
