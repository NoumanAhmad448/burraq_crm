@extends('admin.admin_main')

@section('content')
    @php

        $info = [
            [
                'title' => 'Total Students',
                'count' => $totalStudents,
            ],
            [
                'title' => 'Active Students',
                'count' => $activeStudents,
            ],
            [
                'title' => 'Total Courses',
                'count' => $totalCourses,
            ],
            [
                'title' => 'Active Courses',
                'count' => $activeCourses,
            ],
            [
                'title' => 'Active Enrolled Students',
                'count' => $activeEnrolledStudents,
            ],
        ];

    @endphp
    <div class="row g-3 mb-4">

        @if (count($info) > 0)
            @foreach ($info as $data)
                <div class="col-md-3 m-2" data-aos="fade-up">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <h6 class="text-muted">{{ $data['title'] }}</h6>
                            <h3 class="fw-bold">{{ $data['count'] }}</h3>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
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
