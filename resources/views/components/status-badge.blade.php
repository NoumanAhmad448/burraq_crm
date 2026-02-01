@props(['status'])

@php
    $label = \Illuminate\Support\Str::of($status)->replace('_', ' ')->title();

    $class = statusBadgeClass($status);
@endphp

<span class="badge rounded-pill p-3 w-20 {{ $class }}" style="width: 71%">
    {{ $label }}
</span>
