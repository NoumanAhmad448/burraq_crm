@extends('errors::minimal')

@section('title', __('Page Not Found'))

@section('code')
    <div class="display-3 font-weight-bold text-secondary">
        404
    </div>
@endsection

@section('img')
    <img src="{{ asset('img/404.svg') }}"
         alt="Page not found"
         class="img-fluid mb-4"
         style="max-width: 420px;">
@endsection

@section('message')
    <h4 class="font-weight-semibold text-dark mb-3">
        Page Not Found
    </h4>

    <p class="text-muted mb-4">
        The page you are looking for might have been removed, had its name changed,
        or is temporarily unavailable. Please verify the URL or return to the dashboard.
    </p>

    <a href="{{ route('index') }}" class="btn btn-primary px-4">
        <i class="fa fa-home mr-1"></i> Back to Home
    </a>
@endsection
