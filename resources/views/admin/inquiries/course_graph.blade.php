<div class="chart-container" id="chartContainer"></div>

<div class="d-flex align-items-center justify-content-end mb-3 mt-3 mb-3">
    Show: @foreach([16, 24, 48, 'All'] as $limit)
        <button class="btn btn-sm btn-outline-primary me-2 chart-limit-btn {{ $limit==16 ? 'active' : '' }}" data-limit="{{ $limit }}">
            {{ $limit }}
        </button>
    @endforeach
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function() {

    const courses = @json($displayCourses); // All courses
    const chartContainer = document.getElementById('chartContainer');
    const chartButtons = document.querySelectorAll('.chart-limit-btn');
    const chartsPerRow = 3; // Students, Revenue, Conversion
    const barsPerRow = 8; // 8 courses per row
    const chartHeight = 300; // Fixed height

    function chunkArray(arr, chunkSize) {
        const chunks = [];
        for (let i = 0; i < arr.length; i += chunkSize) {
            chunks.push(arr.slice(i, i + chunkSize));
        }
        return chunks;
    }

    function clearCharts() {
        chartContainer.innerHTML = '';
    }

    function renderCharts(limit) {
        clearCharts();

        let visibleCourses;
        if(limit === 'All') {
            visibleCourses = courses;
        } else {
            visibleCourses = courses.slice(0, parseInt(limit));
        }

        const courseChunks = chunkArray(visibleCourses, barsPerRow);

        courseChunks.forEach((chunk, rowIndex) => {

            // Create a div.row for 3 charts
            const rowDiv = document.createElement('div');
            rowDiv.classList.add('row', 'mb-4');

            ['students','revenue','conversion'].forEach(metric => {
                const colDiv = document.createElement('div');
                colDiv.classList.add('col-lg-4','col-md-12','mb-3');

                // Create canvas
                const canvas = document.createElement('canvas');
                canvas.height = chartHeight;
                canvas.id = `${metric}Chart_${rowIndex}`;

                colDiv.appendChild(canvas);
                rowDiv.appendChild(colDiv);

                // Prepare data
                const labels = chunk.map(c => c.course_name.substring(0,5));
                const fullNames = chunk.map(c => c.course_name);
                let dataSet = [];
                let color = '';

                if(metric === 'students'){
                    dataSet = chunk.map(c => c.students);
                    color = '#36A2EB';
                } else if(metric === 'revenue'){
                    dataSet = chunk.map(c => c.revenue);
                    color = '#FF6384';
                } else {
                    dataSet = chunk.map(c => c.conversion);
                    color = '#FFCE56';
                }

                new Chart(canvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{ label: metric.charAt(0).toUpperCase() + metric.slice(1), data: dataSet, backgroundColor: color }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    title: function(context) {
                                        return fullNames[context[0].dataIndex];
                                    }
                                }
                            }
                        },
                        scales: {
                            x: { ticks: { autoSkip: false } },
                            y: { beginAtZero: true }
                        }
                    }
                });
            });

            chartContainer.appendChild(rowDiv);
        });
    }

    // Default 16 courses (2 rows)
    renderCharts(16);

    // Button click
    chartButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            chartButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const limit = this.getAttribute('data-limit');
            renderCharts(limit);
        });
    });
});

</script>
