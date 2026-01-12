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
            'title' => 'HR',
            'icon' => 'fa-user-circle',
            'route' => route('hr.index'),
            'access_roles' => ['admin'],
        ],
    ];

@endphp

@foreach ($menuItems as $index => $item)
    @if (in_array(auth()->user()->role, $item['access_roles']) || auth()->user()->is_admin)
        <li class="nav-item">
            <div class="text-center py-4 menu-loader{{ $index }}">
                <div class="spinner-border text-primary" role="status"></div>
                <div class="small text-muted mt-2">Loading, please wait…</div>
            </div>
            <a class="nav-link text-dark d-none menu-item" href="{{ $item['route'] }}" index="{{ $index }}">
                <i class="fa {{ $item['icon'] }} mr-2"></i>
                {{ $item['title'] }}
            </a>
        </li>
    @endif
@endforeach
