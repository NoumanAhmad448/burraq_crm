@extends('admin.admin_main')
@section('page-css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style> 
    .amount-orange{
  color: #fd7e14
}
</style>
@endsection
@section('content')
    @php

       $info = [

    [
        'title' => 'Total Students',
        'count' => $activeStudents,
        'icon' => 'img/Total_Studenet.png',
        'amount_color' => 'success',
        'route' => 'students.index',
    ],

    [
        'title' => 'Students (This Month)',
        'count' => $studentsThisMonth->sum('total'),
        'icon' => 'img/fa-fa-users.png',
        'bg' => 'bg-primary',
        'amount_color' => 'purple',
        'route' => 'students.index',
        'route_keys' => ['month' => $month, "year" => $year],
    ],

    [
        'title' => 'Total Overdue Students',
        'count' => $totalOverdue_count,
        'icon' => 'img/fa-fa-users_o.png',
        'bg' => 'bg-danger',
        'amount_color' => 'purple',
        'route' => 'students.index',
        'route_keys' => ['type' => 'overdue'],
    ],

    [
        'title' => 'Total Pending Students',
        'count' => $totalUnpaid_count,
        'icon' => 'img/fa-fa-users-u.png',
        'bg' => 'bg-primary',
        'amount_color' => 'success',
        'route' => 'students.index',
        'route_keys' => ['type' => 'unpaid'],
    ],
    [
        'title' => 'Total Payments (This Month)',
        'count' => show_payment($paymentsThisMonth),
        'icon' => 'img/fa-fa-money.png',
        'bg' => 'bg-success',
        'amount_color' => 'success',
        'route' => null,
        'roles' => ['admin'],
    ],

    [
        'title' => 'Paid Payments (This Month)',
        'count' => show_payment($totalPaid_m),
        'icon' => 'img/fa-fa-money10.png',
        'bg' => 'bg-success',
        'amount_color' => 'success',
        'route' => null,
        'roles' => ['admin'],
    ],

    [
        'title' => 'Pending Payments (This Month)',
        'count' => show_payment($pendingThisMonth),
        'icon' => 'img/fa-fa-money2.png',
        'bg' => 'bg-success',
        'amount_color' => 'orange',
        'route' => null,
        'roles' => ['admin'],
    ],

    [
        'title' => 'Overdue Payments (This Month)',
        'count' => show_payment($dueThisMonth),
        'icon' => 'img/fa-fa-money3.png',
        'bg' => 'bg-success',
        'amount_color' => 'danger',
        'route' => null,
        'roles' => ['admin'],
    ],
    [
        'title' => 'Total Income (This Month)',
        'count' => show_payment($total_income_m),
        'icon' => 'img/fa-fa-check-circle.png',
        'bg' => 'bg-info',
        'amount_color' => 'success',
        'route' => 'students.index',
        'roles' => ['admin'],
    ],
    [
        'title' => 'Total Income',
        'count' => show_payment($total_income),
        'icon' => 'img/fa-fa-check-circle.png',
        'bg' => 'bg-info',
        'amount_color' => 'success',
        'route' => 'students.index',
        'roles' => ['admin'],
    ],

    [
        'title' => 'Total Payment',
        'count' => show_payment($totalPaid_g),
        'icon' => 'img/fa-fa-check-circle1.png',
        'bg' => 'bg-success',
        'amount_color' => 'success',
        'route' => 'students.index',
        'roles' => ['admin'],
    ],

    [
        'title' => 'Total Paid Payment',
        'count' => show_payment($totalPaid),
        'icon' => 'img/fa-fa-check-circle2.png',
        'bg' => 'bg-info',
        'amount_color' => 'success',
        'route' => 'students.index',
        'route_keys' => ['type' => 'paid'],
        'roles' => ['admin'],
    ],

    [
        'title' => 'Total Pending Payment',
        'count' => show_payment($totalUnpaid),
        'icon' => 'img/fa-fa-thumbs-up.png',
        'bg' => 'bg-info',
        'amount_color' => 'orange',
        'route' => 'students.index',
        'route_keys' => ['type' => 'unpaid'],
        'roles' => ['admin'],
    ],

    [
        'title' => 'Total Overdue Payments',
        'count' => show_payment($totalOverdue),
        'icon' => 'img/fa-fa-check-circle.png',
        'bg' => 'bg-info',
        'amount_color' => 'danger',
        'route' => 'students.index',
        'route_keys' => ['type' => 'overdue'],
        'roles' => ['admin'],
    ],

    [
        'title' => 'Certificate',
        'count' => $cert_count,
        'icon' => 'img/fa-fa-thumbs-up fa-2x.png',
        'bg' => 'bg-info',
        'amount_color' => 'primary',
        'route' => 'certificates.index',
        'roles' => ['admin'],
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
<form method="GET" action="{{ route('index') }}" class="form-inline justify-content-end mb-3">
    <x-month_year_filter :month="$month" :year="$year" year_select=false/>
    <button type="submit" class="btn btn-primary btn-sm mb-0">
        Filter
    </button>
</form>


<div class="row g-3 p-1">
    @foreach ($info as $data)
            @php
            $allowed =
                empty($data['roles'])
                || in_array(auth()->user()->role, $data['roles'])
                || auth()->user()->is_admin;

        @endphp

        @if($allowed)
        <div class="col-xl-3 col-md-4 col-sm-6 my-2" data-aos="fade-up">

            @if (!empty($data['route']) && $data['route'] != null)
                <a href="{{ route($data['route'], isset($data['route_keys']) ? $data['route_keys'] : [])}}" class="stat-card-link">
            @endif

            <div class="card shadow-sm stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-wrapper me-3">
                        <img src="{{ asset($data['icon']) }}" class="img_fluid" width="40px"></i>
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
        @endif
    @endforeach
</div>
<x-admin>
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
</x-admin>
@endsection
@section('page-js')
<x-admin>
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

        const paymentLinks = {
            paid: "{{ route('students.index', ['type' => 'paid']) }}",
            unpaid: "{{ route('students.index', ['type' => 'unpaid']) }}",
            overdue: "{{ route('students.index', ['type' => 'overdue']) }}"
        };

        //* ---------- Paid vs Pending ---------- */
        new Chart(document.getElementById('paymentStatusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Paid', 'Pending', 'Overdue'],
                datasets: [{
                    data: [{{ $totalPaid_g }}, {{ $pending }}],
                    backgroundColor: [
                        '#0d6efd', // Paid (Blue)
                        '#dc3545', // Pending (Red)
                        '#000000' // Pending (Red)
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
                },
                responsive: true,
                onClick: function (evt, elements) {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        label = this.data.labels[index].toLowerCase();
                        if(label == "pending"){
                            label="unpaid"
                        }
                        if (paymentLinks[label]) {
                            window.location.href = paymentLinks[label];
                        }
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
</x-admin>
@endsection
