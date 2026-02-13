@props([
    'items' => [],
    'selected' => null,
])

<option value=""> Select </option>
@foreach ($items as $value)
    <option value="{{ $value }}" {{ $selected === $value ? 'selected' : '' }}>
        {{ \Illuminate\Support\Str::of($value)->replace('_', ' ')->title() }}
    </option>
@endforeach
