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

    @include('lib.custom_lib')

    @yield('page-css')
</head>

<body class="d-flex flex-column" style="min-height: 90%">
    <nav class="navbar bg-website">
        @if (config('setting.show_site_log'))
            <a class="navbar-brand text-white" href="{{ route('index') }}">
                CRM
            </a>
        @endif
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('index') }}" target="_blank">
                    <i class="fa fa-home" aria-hidden="true"></i> Home </a>
            </li>
        </ul>
        @if (config('setting.login_profile'))
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('logout_user') }}">
                        <i class="fa fa-sign-out" aria-hidden="true"></i> Logout </a>
                </li>
            </ul>
        @endif
    </nav>
    <div class="container-fluid mt-3">
        <div class="row no-gutters">

            <!-- SIDEBAR COLUMN -->
            <div class="col-md-3" id="sidebar-col">
                <i class="fa fa-bars d-md-none mb-2" id="hamburger"></i>

                <div id="sidebar-wrapper">
                    <ul class="nav flex-column border-right" id="side_menu">
                        @include('admin.sidebar_menu')
                    </ul>
                </div>
            </div>

            <!-- MAIN CONTENT -->
            <div class="col-md-9" id="main-content">
                @yield('content')
            </div>

        </div>
    </div>


    @yield('footer')
