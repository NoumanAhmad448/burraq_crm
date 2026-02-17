@extends('admin.admin_main')

@section('page-css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h4>Inquiry Leads Dashboard</h4>
            </div>
        </div>
        @include('admin.inquiries.leed_filter')
        
        <div class="row mt-4">
            @include('admin.inquiries.course_leed')
        </div>
    </div>
@endsection
