<div class="row mt-4"  id="cardsContainer">
    @foreach ($displayCourses as $data)
        <div class="col-xl-3 col-md-4 col-sm-6 my-2 stat-card-wrapper" data-aos="fade-up">

            <div class="card shadow-sm stat-card">
                <div class="card-body">
                    <div>
                        <h6 class="text-uppercase text-muted small mb-1">
                            {{ $data['course_name'] }}
                        </h6>
                        <h2 class="fw-bold mb-0 amount-primary">
                            {{ $data['students'] }} Total | Students
                        </h2>
                        <p class="mb-0">Revenue: {{ show_payment($data['revenue'], 2) }}</p>
                        <p class="mb-0">Conversion: {{ $data['conversion'] }}%</p>
                    </div>
                </div>
            </div>

        </div>
    @endforeach
</div>
<div class="d-flex align-items-center justify-content-end mb-3">
    <span class="me-2">Show:</span>
    @php
        $limits = [20, 30, 50, 'All'];
    @endphp
    @foreach($limits as $l)
        <button class="btn btn-sm btn-outline-primary me-1 limit-btn {{ $l == 20 ? 'active' : '' }}" data-limit="{{ $l }}">
            {{ $l }}
        </button>
    @endforeach
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const cardWrappers = document.querySelectorAll('.stat-card-wrapper');
    const limitButtons = document.querySelectorAll('.limit-btn');

    function showCards(limit) {
        cardWrappers.forEach((card, index) => {
            if (limit === 'All' || index < parseInt(limit)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Default 20
    showCards(20);

    // Button click
    limitButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all
            limitButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const limit = this.getAttribute('data-limit');
            showCards(limit);
        });
    });
});
</script>
