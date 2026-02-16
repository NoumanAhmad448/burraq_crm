<form method="GET" action="{{ route('inquiries.index') }}" class="mb-3">

    <div class="row justify-content-end align-items-end mb-3 mt-3 mr-5">
        @include("courses", ["courses" => $courses])
        @include("course_status", ["courses" => $courses])
        

        <div class="col-md-3">
            <input type="text" name="due_date" class="form-control form-control-sm datepicker" placeholder="Due date"
                value="{{ request('due_date') }}">
        </div>

        <div class="col-md-2">
            <button type="submit" class="btn btn-primary btn-sm w-100">
                Filter
            </button>
        </div>

    </div>
</form>
