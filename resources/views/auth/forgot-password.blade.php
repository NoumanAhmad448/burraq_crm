@extends('layouts.guest')

@section('page-css')
    {{-- Add any custom CSS if needed --}}
@endsection

@section('content')
    <div class="container d-flex justify-content-center align-items-center py-5">
        <div class="card shadow-sm p-4" style="width: 25rem;">
            <div class="text-center mb-4">
                <img src="{{ asset(config('setting.img_logo_path')) }}" alt="lyskills" class="img-fluid" width="150">
            </div>

            <p class="text-muted mb-4">
                Forgot your password? No problem. Just let us know your email address and we will email you a password reset
                link that will allow you to choose a new one.
            </p>

            <!-- Status message -->
            @if (session('status'))
                <div class="alert alert-info text-center font-weight-bold">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Validation errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                        value="{{ old('email') }}" required autofocus>
                </div>

                <div class="mb-3">
                    {!! NoCaptcha::display() !!}
                    @error('g-recaptcha-response')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        Email Password Reset Link
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var seo =
                "If you forget your password then reset your password to login again on lyskills, an online educational platform.";
            $("#seo_desc").attr('content', seo);
            $("#seo_fb").attr('content', seo);
            $('#seo_title').text('Forgot Password | CRM');
        });
    </script>
@endsection
