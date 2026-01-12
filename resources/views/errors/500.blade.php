@extends('errors::minimal')

@section('title', __('500 Internal Server Error'))
@section('code')
    <div class="display-3 font-weight-bold text-secondary">
        500
    </div>
@endsection

@section('img')
    <img src="{{ asset('img/500.svg') }}"
         alt="500 Server Error"
         class="img-fluid mb-4"
         style="max-width: 420px;">
@endsection

@section('message')
    <h4 class="font-weight-semibold text-dark mb-3">
        Internal Server Error
    </h4>

    <p class="text-muted mb-4">
        Oops! Something went wrong on our end. Our team has been notified,
        and we’re working to fix it. Please try again later.
    </p>

    <a href="{{ route('index') }}" class="btn btn-primary px-4">
        <i class="fa fa-home mr-1"></i> Back to Home
    </a>
@endsection
