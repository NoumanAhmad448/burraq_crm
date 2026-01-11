@extends('admin.admin_main')

@section('content')
    @php

        $info = [
            [
                'title' => 'Total Students',
                'count' => $totalStudents,
                'icon' => 'fa fa-users fa-2x',
                'route' => 'students.index',
            ],
            [
                'title' => 'Active Students',
                'count' => $activeStudents,
                'icon' => 'fa fa-users fa-2x',
                'route' => 'students.index',
            ],
            [
                'title' => 'Total Courses',
                'count' => $totalCourses,
                'icon' => 'fa fa-graduation-cap fa-2x',
                'route' => 'courses.index',
            ],
            [
                'title' => 'Active Courses',
                'count' => $activeCourses,
                'icon' => 'fa fa-thumbs-up fa-2x',
                'route' => 'courses.index',
            ],
            [
                'title' => 'Active Enrolled Students',
                'count' => $activeEnrolledStudents,
                'icon' => 'fa fa-check-circle fa-2x',
                'route' => 'students.index',
            ],
        ];

    @endphp

    <div class="row g-3 p-1">
        @foreach ($info as $data)
            <div class="col-xl-3 col-md-4 col-sm-6 my-2" data-aos="fade-up">

                @if (!empty($data['route']))
                    <a href="{{ route($data['route']) }}" class="stat-card-link">
                @endif

                <div class="card shadow-sm stat-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="icon-wrapper me-3">
                            <i class="{{ $data['icon'] }}"></i>
                        </div>
                        <div class="pl-1">
                            <h6 class="text-uppercase text-muted small mb-1">
                                {{ $data['title'] }}
                            </h6>
                            <h2 class="fw-bold mb-0">
                                {{ $data['count'] }}
                            </h2>
                        </div>
                    </div>
                </div>

                @if (!empty($data['route']))
                    </a>
                @endif

            </div>
        @endforeach
    </div>
@endsection
@section('script')
    @if (config('setting.aos_js'))
        <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
        <script>
            AOS.init();
        </script>
    @endif
@endsection
