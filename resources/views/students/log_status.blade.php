@php
    $status = match ($log->action) {
        'created' => 'success',
        'activated' => 'warning',
        'updated' => 'info',
        'deleted' => 'danger',
        default => 'secondary',
    };

@endphp
<td>
    <span class="badge badge-{{$status}}">
        {{ ucfirst($log->action) }}
    </span>
</td>
