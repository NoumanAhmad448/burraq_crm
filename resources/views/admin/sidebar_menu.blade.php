@php

    $menuItems = [
        [
            'title' => 'Students',
            'icon' => 'fa-users',
            'route' => route('students.index'),
        ],
        [
            'title' => 'Courses',
            'icon' => 'fa-video-camera',
            'route' => route('courses.index'),
        ],
        [
            'title' => 'Enrollments',
            'icon' => 'fa-book',
            'route' => route('course-enrollment'),
        ],
    ];

@endphp

@foreach ($menuItems as $index => $item)
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
@endforeach
