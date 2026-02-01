<form method="GET"
      action="{{ route('inquiries.index') }}"
      class="mb-3">

    <div class="row justify-content-end align-items-end mb-3 mt-3 mr-5">

        <div class="col-md-4">
            <select name="type"
                    class="form-control form-control-sm"
                    {{-- onchange="this.form.submit()" --}}
                    >

                <option value="all">All Active</option>

                <optgroup label="Status">
                    <option value="pending" {{ request('type') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="contacted" {{ request('type') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                    <option value="follow_up" {{ request('type') == 'follow_up' ? 'selected' : '' }}>Follow Up</option>
                    <option value="not_interested" {{ request('type') == 'not_interested' ? 'selected' : '' }}>Not Interested</option>
                    <option value="not_contacted" {{ request('type') == 'not_contacted' ? 'selected' : '' }}>Not Contacted</option>
                </optgroup>

                <optgroup label="This Month">
                    <option value="this_month_pending" {{ request('type') == 'this_month_pending' ? 'selected' : '' }}>
                        Pending (This Month)
                    </option>
                    <option value="this_month_contacted" {{ request('type') == 'this_month_contacted' ? 'selected' : '' }}>
                        Contacted (This Month)
                    </option>
                </optgroup>

            </select>
        </div>

        <div class="col-md-3">
            <input type="text"
                   name="due_date"
                   class="form-control form-control-sm datepicker"
                   placeholder="Due date"
                   value="{{ request('due_date') }}">
        </div>

        <div class="col-md-2">
            <button type="submit"
                    class="btn btn-primary btn-sm w-100">
                Filter
            </button>
        </div>

    </div>
</form>
