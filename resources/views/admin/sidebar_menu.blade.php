@php

    $menuItems = [
        [
            'title' => 'Students',
            'icon' => 'fa-users',
            'route' => route('students.index'),
            'access_roles' => ['admin', 'hr'],
        ],
        [
            'title' => 'Courses',
            'icon' => 'fa-video-camera',
            'route' => route('courses.index'),
            'access_roles' => ['admin', 'hr'],
        ],
        [
            'title' => 'Inquiries',
            'icon' => 'fa-question-circle',
            'route' => route('inquiries.index'),
            'access_roles' => ['admin', 'hr'],
        ],
        [
            'title' => 'Certificates',
            'icon' => 'fa-certificate',
            'route' => route('certificates.index'),
            'access_roles' => ['admin', 'hr'],
        ],
        [
            'title' => 'Generated Certificates',
            'icon' => 'fa-check-circle',
            'route' => route('certificates.index', ["type" => "paid"]),
            'access_roles' => ['admin', 'hr'],
        ],
        [
            'title' => 'HR',
            'icon' => 'fa-user-circle',
            'route' => route('hr.index'),
            'access_roles' => ['admin'],
        ],
    ];

@endphp

@foreach ($menuItems as $index => $item)
    @if (in_array(auth()->user()->role, $item['access_roles']) || auth()->user()->is_admin)
        <li class="nav-item text-center px-3">
            <!-- Loader -->
            <div class="py-3 menu-loader{{ $index }}">
                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                <div class="medium text-muted mt-1">Loading…</div>
            </div>

            <!-- Menu Link -->
            {{-- <a class="nav-link navbar-text text-white d-none menu-item" --}}
            <a class="navbar-text text-white d-none menu-item"
               href="{{ $item['route'] }}"
               data-index="{{ $index }}">
                <i class="fa {{ $item['icon'] }} d-block mb-1"></i>
                <small>{{ $item['title'] }}</small>
            </a>
        </li>
    @endif
@endforeach

