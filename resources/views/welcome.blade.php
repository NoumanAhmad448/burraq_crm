@extends('admin.admin_main')
@section('page-css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
@section('content')
    @php

        $info = [
            [
                'title' => 'Total Students',
                'count' => $totalStudents,
                'icon' => 'fa fa-users fa-2x',
                'amount_color' => 'primary',      // cyan   (informational)

                'route' => 'students.index',
            ],
            [
                'title' => 'Active Students',
                'count' => $activeStudents,
                'icon' => 'fa fa-users fa-2x',
                'amount_color' => 'success',      // cyan   (informational)

                'route' => 'students.index',
            ],
            [
                'title' => 'Total Courses',
                'count' => $totalCourses,
                'icon' => 'fa fa-graduation-cap fa-2x',
                'amount_color' => 'danger',      // cyan   (informational)

                'route' => 'courses.index',
            ],
            [
                'title' => 'Active Courses',
                'count' => $activeCourses,
                'icon' => 'fa fa-thumbs-up fa-2x',
                'amount_color' => 'purple',      // cyan   (informational)

                'route' => 'courses.index',
            ],
            [
                'title' => 'Active Enrolled Students',
                'count' => $activeEnrolledStudents,
                'icon' => 'fa fa-check-circle fa-2x',
                'amount_color' => 'info',      // cyan   (informational)

                'route' => 'students.index',
            ],
            [
                'title' => 'Students (This Month)',
                'count' => $studentsThisMonth->sum('total'),
                'icon' => 'fa fa-users',
                'bg' => 'bg-primary',
                'amount_color' => 'purple',      // cyan   (informational)

                'route' => 'students.index',
            ],
            [
                'title' => 'Payments This Month',
                'count' => show_payment($paymentsThisMonth),
                'icon' => 'fa fa-money',
                'bg' => 'bg-success',
                'amount_color' => 'success',      // cyan   (informational)

                'route' => null,
            ],
            [
                'title' => 'Total Paid',
                'count' => show_payment($totalPaid),
                'icon' => 'fa fa-check-circle',
                'bg' => 'bg-info',
                'route' => 'students.index',
                'amount_color' => 'danger',      // cyan   (informational)

                "route_keys" => ["type" => "paid"]
            ],
            [
                'title' => 'Unpaid Payment',
                'count' => show_payment($totalUnpaid),
                'icon' => 'fa fa-check-circle',
                'bg' => 'bg-info',
                'route' => 'students.index',
                'amount_color' => 'info',      // cyan   (informational)

                "route_keys" => ["type" => "unpaid"]
            ],
            [
                'title' => 'Overdue Payments',
                'count' => show_payment($totalOverdue),
                'icon' => 'fa fa-check-circle',
                'bg' => 'bg-info',
                'route' => 'students.index',
                'amount_color' => 'primary',   // blue   (neutral totals)

                "route_keys" => ["type" => "overdue"]
            ],
            [
                'title' => 'Paid Certificate',
                'count' => $cert_count,
                'icon' => 'fa fa-check-circle',
                'bg' => 'bg-info',
                'route' => 'certificates.index',
                'amount_color' => 'danger',    // red    (overdue / pending)

                "route_keys" => ["type" => "paid"]
            ],
            [
                'title' => 'Pending Amount',
                'count' => show_payment($pending),
                'icon' => 'fa fa-exclamation-circle',
                'bg' => 'bg-danger',
                'route' => null,
                'amount_color' => 'success',   // green  (paid / positive)

            ],
        ];

    @endphp

<!-- Toggle Button Top-Right -->
<!-- Toggle Button Top-Right -->
<div class="d-flex justify-content-end mb-2">
    <button id="toggle-amounts" class="btn btn-sm btn-outline-secondary" text="1">
        Hide Amounts
    </button>
</div>

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




    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <canvas id="studentsMonthChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <canvas id="studentsYearChart" class="mt-4"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <canvas id="annualPaymentsChart" class="mt-4"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <canvas id="paymentStatusChart" class="mt-4"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-js')
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
        if ($(this).attr("text") === '1') {
            $(this).text('Show Amounts');
            $(this).attr("text") = '2'
        } else {
            $(this).text('Hide Amounts');
            $(this).attr("text") = '1'

        }
    });
});
</script>


@endsection
