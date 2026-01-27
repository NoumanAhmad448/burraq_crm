<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        @if (isset($title))
            {{ __('messages.' . $title) }}
        @else
            {{ __('messages.admin') }}
        @endif
    </title>
    <meta name="description"
        content="@if (isset($desc)) {{ $desc }} @else {{ __('description.default') }} @endif">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">
    <!-- jQuery MUST come first -->

    @include('lib.custom_lib')

    @yield('page-css')
</head>

<body class="d-flex flex-column min-vh-100">
   <nav class="navbar navbar-expand-lg bg-website px-3">

    {{-- Left side --}}
    @if (config('setting.show_site_log'))
        <a class="navbar-brand text-white" href="{{ route('index') }}">
            <img
                src="{{ asset(config('setting.img_logo_path')) }}"
                alt="lyskills"
                class="img-fluid mx-auto d-block"
                width="60"
            >
        </a>
    @endif

    {{-- Mobile toggle --}}
    <button class="navbar-toggler text-white border-0" type="button"
            data-toggle="collapse" data-target="#mainNavbar">
        <i class="fa fa-bars"></i>
    </button>

    {{-- Center / Main menu --}}
    <div class="collapse navbar-collapse" id="mainNavbar">
        <ul class="navbar-nav mx-auto" id="side_menu">
            @include('admin.sidebar_menu')
        </ul>

        {{-- Right side --}}
        @auth
            <ul class="navbar-nav ml-auto align-items-center">
                <li class="nav-item mr-3">
                    <span class="navbar-text text-white">
                        Welcome, {{ ucfirst(auth()->user()->name) }}
                    </span>
                </li>
                {{-- <x-notification/> --}}
                @if (config('setting.login_profile'))
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('logout_user') }}">
                            <i class="fa fa-sign-out"></i> Logout
                        </a>
                    </li>
                @endif
            </ul>
        @endauth
    </div>
</nav>


<div class="container-fluid mt-3 flex-grow-1">
<div class="row no-gutters">
<!-- MAIN CONTENT -->
        <div class="col-md-12" id="main-content">
            @yield('content')
        </div>
    </div>
</div>
@yield('footer')
</body>
</html>
{{-- <div class="container-fluid mt-3"> --}}
{{-- </div> --}}


