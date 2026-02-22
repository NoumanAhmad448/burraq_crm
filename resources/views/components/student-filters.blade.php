@props([
    'all_courses' => $all_courses,
])
<div class="row mb-3 mt-3 mr-5">
    <div class="col-md-12 d-flex justify-content-end">
        <form method="GET" action="{{ route('students.index') }}" class="form-inline justify-content-end mb-3">
            <div class="form-group mr-2">
                @include('courses', ['courses' => $all_courses, 'design' => true])
            </div>
            <div class="form-group mr-2">
                @include('admin.groups.group_listing', ["hide_labl" => true])
            </div>
            <x-month_year_filter :month="$month" :year="$year" year_select=true />
            <div class="form-group mr-2">

                <select name="type" class="form-control form-control-sm select" id="status">

                    <option value="">-- All Statuses --</option>
                    {{-- <option value="deleted" {{ request('type') == 'deleted' ? 'selected' : '' }}>
                            Deleted
                        </option> --}}
                    <option value="unpaid" {{ request('type') == 'unpaid' ? 'selected' : '' }}>
                        Unpaid
                    </option>
                    <option value="paid" {{ request('type') == 'paid' ? 'selected' : '' }}>
                        Paid
                    </option>
                    <option value="overdue" {{ request('type') == 'overdue' ? 'selected' : '' }}>
                        Overdue
                    </option>
                    <option value="{{ App\Models\EnrolledCourse::DROPPED }}" {{ request('type') == App\Models\EnrolledCourse::DROPPED ? 'selected' : '' }}>
                        {{ humanize(App\Models\EnrolledCourse::DROPPED) }}
                    </option>
                    </option>
                    <option value="{{ App\Models\EnrolledCourse::COMPLETED }}" {{ request('status') == App\Models\EnrolledCourse::COMPLETED ? 'selected' : '' }}>
                        {{  humanize(App\Models\EnrolledCourse::COMPLETED) }}
                    </option>
                    {{-- <option value="certificate_issued" {{ request('type') == 'certificate_issued' ? 'selected' : '' }}>
                            Certificate Issued
                        </option> --}}
                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-sm mb-0">
                Filter
            </button>

        </form>
    </div>
</div>

<script>
document.getElementById('status').addEventListener('change', function () {
    if (this.value === 'Completed') {
        this.setAttribute('name', 'status');
    } else {
        this.setAttribute('name', 'type');
    }
});
</script>