@props([
    'items' => [],
    'selected' => null,
    "default" => null
])

@notempty($default)
<option value=""> Select </option>    
@endnotempty

@foreach ($items as $value)
    <option value="{{ $value }}" {{ $selected === $value ? 'selected' : '' }}>
        {{ \Illuminate\Support\Str::of($value)->replace('_', ' ')->title() }}
    </option>
@endforeach
