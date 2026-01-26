@extends('admin.admin_main')

@section('content')
    <div class="container-fluid">

        {{-- Page Header --}}
        <div class="row mb-4">
            <div class="col-12">
                <h3 class="mb-1">Inquiry Dashboard</h3>
                <p class="text-muted mb-0">Overview of all inquiry activities</p>
            </div>
        </div>

        @php
            $cards = [
                [
                    'title' => 'Total Inquiries',
                    'key' => 'total',
                    'icon' => 'fa fa-database',
                    'img' => null,
                    'color' => 'primary',
                ],
                [
                    'title' => 'Pending',
                    'key' => 'pending',
                    'icon' => 'fa fa-clock-o',
                    'img' => null,
                    'color' => 'warning',
                ],
                [
                    'title' => 'Contacted',
                    'key' => 'contacted',
                    'icon' => 'fa fa-phone',
                    'img' => null,
                    'color' => 'success',
                ],
                [
                    'title' => 'Follow Up',
                    'key' => 'follow_up',
                    'icon' => 'fa fa-refresh',
                    'img' => null,
                    'color' => 'info',
                ],
                [
                    'title' => 'Not Interested',
                    'key' => 'not_interested',
                    'icon' => 'fa fa-ban',
                    'img' => null,
                    'color' => 'danger',
                ],
                [
                    'title' => 'This Month Pending',
                    'key' => 'this_month_pending',
                    'icon' => 'fa fa-calendar',
                    'img' => null,
                    'color' => 'secondary',
                ],
                [
                    'title' => 'This Month Contacted',
                    'key' => 'this_month_contact',
                    'icon' => 'fa fa-check-circle',
                    'img' => null,
                    'color' => 'dark',
                ],
                [
                    'title' => 'Not Contacted',
                    'key' => 'not_contacted',
                    'icon' => 'fa fa-user-times',
                    'img' => null,
                    'color' => 'secondary',
                ],
            ];
        @endphp

        {{-- Dashboard Cards --}}
        <div class="row">
            @foreach ($cards as $card)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <a href="{{ route('inquiries.index', ['type' => $card['key']]) }}"
                                   class="text-decoration-none text-dark">
                        <div class="card-body d-flex align-items-center">

                            {{-- Icon / Image --}}
                            <div class="mr-3">
                                @if ($card['img'])
                                    <img src="{{ asset('img/' . $card['img']) }}" width="40">
                                @else
                                    <span class="badge badge-{{ $card['color'] }} p-3">
                                        <i class="{{ $card['icon'] }} fa-lg"></i>
                                    </span>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div>
                                <h4 class="mb-0 font-weight-bold">
                                    {{ $data[$card['key']] ?? 0 }}
                                </h4>
                                <small class="text-muted">
                                    {{ $card['title'] }}
                                </small>
                            </div>

                        </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>


         {{-- CHARTS --}}
        <div class="row">

            {{-- STATUS WISE --}}
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary text-white text-center">
                        <i class="fa fa-pie-chart"></i>
                        <strong>Status Wise Inquiries</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="statusChart" height="250"></canvas>
                    </div>
                </div>
            </div>

            {{-- MONTHLY TREND --}}
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-success text-white text-center">
                        <i class="fa fa-bar-chart"></i>
                        <strong>Monthly Inquiry Trend</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="monthlyChart" height="250"></canvas>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
@section('page-js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // ------------------------
    // Status Wise Doughnut Chart
    // ------------------------
    const statusLabels = {!! json_encode(array_keys($data['statusWise'] )) !!};
    const statusData   = {!! json_encode(array_values($data['statusWise'])) !!};
    const statusColors = ['#ffc107','#28a745','#17a2b8','#dc3545']; // Pending, Contacted, Follow Up, Not Interested

    new Chart(document.getElementById('statusChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusData,
                backgroundColor: statusColors,
                borderWidth: 1
            }]
        },
        options: {
            cutout: '65%',
            plugins: {
                legend: { position: 'bottom' },
                tooltip: { mode: 'index' }
            },
            onClick: function(evt, elements) {
                if (!elements.length) return;
                const index = elements[0].index;
                const type  = statusLabels[index].toLowerCase().replace(/ /g,'_');
                window.location.href = `{{ route('inquiries.index') }}?type=${type}`;
            }
        }
    });

    // ------------------------
    // Monthly Trend Bar Chart
    // ------------------------
    const monthLabels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const monthlyData = {!! json_encode(array_values($data['monthlyCount'])) !!};

    new Chart(document.getElementById('monthlyChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Inquiries',
                data: monthlyData,
                backgroundColor: '#0d6efd'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endsection

