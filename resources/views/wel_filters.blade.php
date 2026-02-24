<!-- Toggle Button Top-Right -->
<!-- Toggle Button Top-Right -->
<div class="d-flex justify-content-end mb-2">
    <button id="toggle-amounts" class="btn btn-sm btn-outline-secondary" text="1">
        Hide Amounts
    </button>
</div>
<form method="GET" action="{{ route('index') }}" class="form-inline justify-content-end mb-3">
    <x-month_year_filter :month="$month" :year="$year" year_select=false />
    <button type="submit" class="btn btn-primary btn-sm mb-0">
        Filter
    </button>
</form>
