@foreach ($inquiryCounts as $item)
    <div class="col-md-3">
        <div class="card shadow-sm mb-4">
            <a href="{{ route('inquiries.index', array_merge(request()->all(), ['course_id' => $item->course_id])) }}" class="stat-card-link">
                <div class="card shadow-sm stat-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="pl-1">

                            <h6 class="text-uppercase text-muted small mb-1">
                                {{ $item->course->name ?? 'Unknown Course' }}
                            </h6>

                            <h2 class="fw-bold mb-0 amount-primary">
                                Total | {{ $item->total }}
                            </h2>
                        </div>
                    </div>
                </div>
            </a>

        </div>
    </div>
@endforeach
