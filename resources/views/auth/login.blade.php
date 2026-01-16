@extends('layouts.guest')

@section('page-css')
    {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}">         --}}
@endsection
@section('content')
    {{-- <x-jet-authentication-card> --}}

    {{-- <x-jet-validation-errors class="mb-4" /> --}}

    @if (session('status'))
        <div class="mb-4 text-sm text-blue-700 text-primary bg-blue-100 text-center font-bold">
            {{ session('status') }}
        </div>
    @endif

    <div class="d-flex justify-content-center align-items-center min-vh-100">
    <div class="col-md-4 col-sm-10">
        <div class="text-center mb-4">
    <img
        src="{{ asset(config('setting.img_logo_path')) }}"
        alt="lyskills"
        class="img-fluid mx-auto d-block"
        width="150"
    >
    @include('messages')
</div>

        <form method="POST" action="{{ route('login') }}" class="text-center">
            @csrf

            <!-- Email -->
            <div class="form-group text-left">
                <label for="email" class="form-label">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="Email address"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
            </div>

            <!-- Password -->
            <div class="form-group text-left">
                <label for="password" class="form-label">Password</label>

                <div class="input-group">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Password min 8 digits"
                        required
                        autocomplete="new-password"
                    >
                    <div class="input-group-append">
                        <span
                            class="input-group-text bg-website text-white cursor-pointer"
                            id="show_pass"
                        >
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="form-group text-left mt-2">
                <div class="form-check">
                    <input
                        id="remember_me"
                        type="checkbox"
                        class="form-check-input"
                        name="remember"
                    >
                    <label class="form-check-label" for="remember_me">
                        Remember me
                    </label>
                </div>
            </div>

            <!-- Captcha -->
            <div class="form-group mt-3">
                {!! NoCaptcha::renderJs() !!}
                {!! NoCaptcha::display() !!}
                @error('g-recaptcha-response')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>

            <!-- Actions -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                @if (Route::has('password.request'))
                    <a
                        href="{{ route('password.request') }}"
                        class="text-sm text-muted"
                    >
                        Forgot your password?
                    </a>
                @endif

                <button type="submit" class="btn bg-website text-white">
                    Login
                </button>
            </div>

        </form>

    </div>
</div>

    {{-- </x-jet-authentication-card> --}}
@endsection
@section('script')
    <script>
        $(function() {

            var showPassword = (pass) => {
                if (pass.attr('type') === "password") {
                    pass.attr('type', 'text');
                } else {
                    pass.attr('type', 'password');
                }
            }

            var pass = $('#show_pass');
            pass.click(function() {
                var other_el = $('#password');
                showPassword(other_el);
            });


        });
    </script>
    <script>
        var seo =
            "Login  CRM";
        $("#seo_desc").attr('content',
            seo);
        $("#seo_fb").attr('content', seo);
        $('#seo_title').text('Login | CRM');
    </script>
@endsection
{{-- </x-guest-layout> --}}
