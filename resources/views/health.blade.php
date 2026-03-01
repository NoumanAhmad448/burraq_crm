@extends('admin.admin_main')

@section('page-css')
@endsection

@section('content')
    @php
        $constant = 'test';
    @endphp

    <div class="container mt-4">
        <div class="text-center mb-4">
            <h4 class="font-weight-bold">
                {{ __('health::notifications.laravel_health') }}
            </h4>
            <div class="my-3">
                <x-health-logo />
            </div>

            @if ($lastRanAt)
                <div id="{{ $constant }}notifications"
                    class="small font-weight-medium {{ $lastRanAt->diffInMinutes() > 5 ? 'text-danger' : 'text-secondary' }}">
                    {{ __('health::notifications.check_results_from') }} {{ $lastRanAt->diffForHumans() }}
                </div>
            @endif
        </div>

        <div class="px-2 my-4 container">
            @if (count($checkResults?->storedCheckResults ?? []))
                <div class="row">
                    @foreach ($checkResults->storedCheckResults as $key => $result)
                        <div id="{{ $constant }}{{ $key }}{{ $constant }}" class="col-3 p-3 mb-4"
                            style="min-height:130px;">
                            <div class="card h-100 shadow-sm rounded-xl">
                                <div class="card-body d-flex align-items-start">
                                    <!-- <x-health-status-indicator :result="$result" /> -->
                                    <div>
                                        <dd class="font-weight-bold text-dark mb-1" style="font-size:1.1rem;">
                                            {{ $result->label }}
                                        </dd>
                                        <dt class="text-secondary small mb-0">
                                            @if (!empty($result->notificationMessage))
                                                {{ $result->notificationMessage }}
                                            @else
                                                {{ $result->shortSummary }}
                                            @endif
                                        </dt>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endsection
    @section('script')
    @endsection
