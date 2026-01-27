@props([
    "year_select" => false,
    "month",
    "year"
])
<div class="form-group mr-2 mb-0">
    <label for="month" class="mr-1">Month</label>
    <select name="month" id="month" class="form-control form-control-sm">
        <option value=""> -- Select Month --
        </option>

        @for ($m = 1; $m <= 12; $m++)
            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
            </option>
        @endfor
    </select>
</div>

<div class="form-group mr-2 mb-0">
    <label for="year" class="mr-1">Year</label>
    <select name="year" id="year" class="form-control form-control-sm">
        @if ($year_select)
            <option value=""> -- Select Year --
        @endif
        @for ($y = 2023; $y <= 2035; $y++)
            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                {{ $y }}
            </option>
        @endfor
    </select>
</div>
