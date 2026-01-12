@extends('errors::minimal')

@section('title', __('403 Forbidden'))
@section('code')
    <div class="display-3 font-weight-bold text-secondary">
        403
    </div>
@endsection

@section('img')
    <img src="{{ asset('img/403.svg') }}"
         alt="403 Forbidden"
         class="img-fluid mb-4"
         style="max-width: 420px;">
@endsection

@section('message')
    <h4 class="font-weight-semibold text-dark mb-3">
        Access Denied
    </h4>

    <p class="text-muted mb-4">
        You do not have permission to view this page. Please check your access rights
        or contact support if you believe this is an error.
    </p>

    <a href="{{ route('index') }}" class="btn btn-primary px-4">
        <i class="fa fa-home mr-1"></i> Go to Home
    </a>
@endsection
