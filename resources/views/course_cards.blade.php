<div class="row g-3 p-1">
    @foreach ($info as $data)
        @php
            $allowed =
                empty($data['roles']) || in_array(auth()->user()->role, $data['roles']) || auth()->user()->is_admin;

        @endphp

        @if ($allowed)
            <div class="col-xl-3 col-md-4 col-sm-6 my-2" data-aos="fade-up">

                @if (!empty($data['route']) && $data['route'] != null)
                    <a href="{{ route($data['route'], isset($data['route_keys']) ? $data['route_keys'] : []) }}"
                        class="stat-card-link">
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
