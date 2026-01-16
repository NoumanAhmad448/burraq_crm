@extends('admin.admin_main')
@section('page-css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
@section('content')
    @php

        $info = [
            // [
            //     'title' => 'Total Students',
            //     'count' => $totalStudents,
            //     'icon' => 'fa fa-users fa-2x',
            //     'amount_color' => 'primary',      // cyan   (informational)

            //     'route' => 'students.index',
            // ],
            [
                'title' => 'Total Students',
                'count' => $activeStudents,
                'icon' => 'fa fa-users fa-2x',
                'amount_color' => 'success',      // cyan   (informational)
                'route' => 'students.index',
            ],
            // [
            //     'title' => 'Total Courses',
            //     'count' => $totalCourses,
            //     'icon' => 'fa fa-graduation-cap fa-2x',
            //     'amount_color' => 'danger',      // cyan   (informational)

            //     'route' => 'courses.index',
            // ],
            // [
            //     'title' => 'Active Courses',
            //     'count' => $activeCourses,
            //     'icon' => 'fa fa-thumbs-up fa-2x',
            //     'amount_color' => 'purple',      // cyan   (informational)

            //     'route' => 'courses.index',
            // ],
            // [
            //     'title' => 'Active Enrolled Students',
            //     'count' => $activeEnrolledStudents,
            //     'icon' => 'fa fa-check-circle fa-2x',
            //     'amount_color' => 'info',      // cyan   (informational)

            //     'route' => 'students.index',
            // ],
            [
                'title' => 'Students (This Month)',
                'count' => $studentsThisMonth->sum('total'),
                'icon' => 'fa fa-users',
                'bg' => 'bg-primary',
                'amount_color' => 'purple',      // cyan   (informational)

                'route' => 'students.index',
            ],
            [
                'title' => 'Payments (This Month)',
                'count' => show_payment($paymentsThisMonth),
                'icon' => 'fa fa-money',
                'bg' => 'bg-success',
                'amount_color' => 'success',      // cyan   (informational)

                'route' => null,
            ],
            [
                'title' => 'Pending Payments (This Month)',
                'count' => show_payment($pendingThisMonth),
                'icon' => 'fa fa-money',
                'bg' => 'bg-success',
                'amount_color' => 'danger',      // cyan   (informational)

                'route' => null,
            ],
            [
                'title' => 'Overdue Payments (This Month)',
                'count' => show_payment($dueThisMonth),
                'icon' => 'fa fa-money',
                'bg' => 'bg-success',
                'amount_color' => 'info',      // cyan   (informational)

                'route' => null,
            ],
            [
                'title' => 'Total Paid Payment',
                'count' => show_payment($totalPaid),
                'icon' => 'fa fa-check-circle',
                'bg' => 'bg-info',
                'route' => 'students.index',
                'amount_color' => 'danger',      // cyan   (informational)

                "route_keys" => ["type" => "paid"]
            ],
            [
                'title' => 'Total Pending Payment',
                'count' => show_payment($totalUnpaid),
                'icon' => 'fa fa-thumbs-up fa-2x',
                'bg' => 'bg-info',
                'route' => 'students.index',
                'amount_color' => 'info',      // cyan   (informational)

                "route_keys" => ["type" => "unpaid"]
            ],
            [
                'title' => 'Total Overdue Payments',
                'count' => show_payment($totalOverdue),
                'icon' => 'fa fa-check-circle',
                'bg' => 'bg-info',
                'route' => 'students.index',
                'amount_color' => 'primary',   // blue   (neutral totals)

                "route_keys" => ["type" => "overdue"]
            ],
            [
                'title' => 'Certificate',
                'count' => $cert_count,
                'icon' => 'fa fa-thumbs-up fa-2x',
                'bg' => 'bg-info',
                'route' => 'certificates.index',
                'amount_color' => 'danger',    // red    (overdue / pending)
            ],
            // [
            //     'title' => 'Pending Amount',
            //     'count' => show_payment($pending),
            //     'icon' => 'fa fa-exclamation-circle',
            //     'bg' => 'bg-danger',
            //     'route' => null,
            //     'amount_color' => 'success',   // green  (paid / positive)

            // ],
        ];

    @endphp

<!-- Toggle Button Top-Right -->
<!-- Toggle Button Top-Right -->
<div class="d-flex justify-content-end mb-2">
    <button id="toggle-amounts" class="btn btn-sm btn-outline-secondary" text="1">
        Hide Amounts
    </button>
</div>
<form method="GET" action="{{ route('index') }}" class="form-inline justify-content-end mb-3">

    {{-- Month --}}
    <div class="form-group mr-2 mb-0">
        <label for="month" class="mr-1">Month</label>
        <select name="month" id="month" class="form-control form-control-sm">
            @for ($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                </option>
            @endfor
        </select>
    </div>

    {{-- Year --}}
    <div class="form-group mr-2 mb-0">
        <label for="year" class="mr-1">Year</label>
        <select name="year" id="year" class="form-control form-control-sm">
            @for ($y = 2023; $y <= 2035; $y++)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                    {{ $y }}
                </option>
            @endfor
        </select>
    </div>

    {{-- Filter Button --}}
    <button type="submit" class="btn btn-primary btn-sm mb-0">
        Filter
    </button>

</form>




<div class="row g-3 p-1">
    @foreach ($info as $data)
        <div class="col-xl-3 col-md-4 col-sm-6 my-2" data-aos="fade-up">

            @if (!empty($data['route']) && $data['route'] != null)
                <a href="{{ route($data['route'], isset($data['route_keys']) ? $data['route_keys'] : [])}}" class="stat-card-link">
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
                        <h2 class="fw-bold mb-0 amount-{{ $data['amount_color'] ?? 'primary' }}">
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
@if(auth()->user()->is_admin)
<div class="row justify-content-center mb-3">
    <div class="col-md-5 d-flex align-items-center justify-content-center">
        <div class="card w-100">
            <div class="card-body d-flex align-items-center justify-content-center" style="height:300px;">
                <canvas id="studentsMonthChart" style="max-width:100%; max-height:100%;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-5 d-flex align-items-center justify-content-center">
        <div class="card w-100">
            <div class="card-body d-flex align-items-center justify-content-center" style="height:300px;">
                <canvas id="studentsYearChart" style="max-width:100%; max-height:100%;"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center mb-3">
    <div class="col-md-5 d-flex align-items-center justify-content-center">
        <div class="card w-100">
            <div class="card-body d-flex align-items-center justify-content-center" style="height:300px;">
                <canvas id="annualPaymentsChart" style="max-width:100%; max-height:100%;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-5 d-flex align-items-center justify-content-center">
        <div class="card w-100">
            <div class="card-body d-flex align-items-center justify-content-center" style="height:300px;">
                <canvas id="paymentStatusChart" style="max-width:100%; max-height:100%;"></canvas>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
@section('page-js')
@if(auth()->user()->is_admin)

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        /* ---------- Students This Month ---------- */
        new Chart(document.getElementById('studentsMonthChart'), {
            type: 'bar',
            data: {
                labels: @json($studentsThisMonth->pluck('date')),
                datasets: [{
                    label: 'Students Registered',
                    data: @json($studentsThisMonth->pluck('total')),
                    backgroundColor: '#0d6efd',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            }
        });

        /* ---------- Students Yearly ---------- */
        new Chart(document.getElementById('studentsYearChart'), {
            type: 'line',
            data: {
                labels: @json($studentsYearly->pluck('month')->map(fn($m) => Carbon\Carbon::create()->month($m)->format('M'))),
                datasets: [{
                    label: 'Students (Yearly)',
                    data: @json($studentsYearly->pluck('total')),
                    fill: false,
                    tension: 0.3,
                    backgroundColor: '#0d6efd',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            }
        });

        /* ---------- Annual Payments ---------- */
        new Chart(document.getElementById('annualPaymentsChart'), {
            type: 'line',
            data: {
                labels: @json($annualPayments->pluck('month')->map(fn($m) => Carbon\Carbon::create()->month($m)->format('M'))),
                datasets: [{
                    label: 'Payments (Yearly)',
                    data: @json($annualPayments->pluck('total')),
                    fill: false,
                    tension: 0.3,
                    backgroundColor: '#0d6efd',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            }
        });

        //* ---------- Paid vs Pending ---------- */
        new Chart(document.getElementById('paymentStatusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Paid', 'Pending'],
                datasets: [{
                    data: [{{ $totalPaid }}, {{ $pending }}],
                    backgroundColor: [
                        '#0d6efd', // Paid (Blue)
                        '#dc3545' // Pending (Red)
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
<script>
$(document).ready(function() {
    $('#toggle-amounts').click(function() {
        // loop through all amount fields
        $('h2').each(function() {
            var classes = $(this).attr('class');
            if (classes && classes.indexOf('amount-') !== -1) {
                // toggle visibility manually
                if ($(this).css('display') === 'none') {
                    $(this).css('display', 'block'); // show
                } else {
                    $(this).css('display', 'none');  // hide
                }
            }
        });

        // toggle button text
        if ($("#toggle-amounts").attr("text") == 1) {
            $(this).text('Show Amounts');
            $(this).attr('text', 2); // ✔ valid

        } else {
            $(this).text('Hide Amounts');
            $(this).attr('text', 1); // ✔ valid


        }
    });
});
</script>
@endif

@endsection
