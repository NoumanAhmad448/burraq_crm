@props([
    'items' => [],
    'selected' => null,
])

@foreach ($items as $value)
    <option value="{{ $value }}" {{ $selected === $value ? 'selected' : '' }}>
        {{ \Illuminate\Support\Str::of($value)->replace('_', ' ')->title() }}
    </option>
@endforeach
