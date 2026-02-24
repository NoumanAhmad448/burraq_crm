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
                data: [{{ $totalPaid }}, {{ $totalUnpaid }}, {{ $totalOverdue }}],
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
            onClick: function(evt, elements) {
                if (elements.length > 0) {
                    const index = elements[0].index;
                    label = this.data.labels[index].toLowerCase();
                    if (label == "pending") {
                        label = "unpaid"
                    }
                    if (paymentLinks[label]) {
                        window.location.href = paymentLinks[label];
                    }
                }
            }
        }
    });
</script>
