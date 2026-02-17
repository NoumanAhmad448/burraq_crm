@extends('admin.admin_main')

@section('page-css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    {{-- Include filter --}}
    @include('admin.inquiries.leed_filter', ["route" => route("course_dashboard.index")])
    @include('admin.inquiries.course_card')
    @include('admin.inquiries.course_graph')
    @include('admin.inquiries.course_table')

@endsection

@section('page-js')
@endsection
