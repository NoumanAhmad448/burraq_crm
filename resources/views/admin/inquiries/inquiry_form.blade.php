<div class="card">
    <div class="card-header">
        <h5>
            @if ($is_update)
                Edit Inquiry
            @else
                Create Inquiry
            @endif
        </h5>
    </div>

    @include('messages')

    <div class="card-body">
        <form method="POST"
            action="@if ($is_update) {{ route('inquiries.update', $inquiry?->id) }} @else {{ route('inquiries.store') }} @endif">
            @csrf

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control"
                            value="{{ $is_update ? $inquiry->name : old('name') }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control"
                            value="{{ $is_update ? $inquiry->phone : old('phone') }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" name="email" class="form-control"
                            value="{{ $is_update ? $inquiry->email : old('email') }}">
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Interested Course</label>
                        <select name="course_id" class="form-control select">
                            <option value="">Select Course</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}"
                                    {{ ($is_update && $inquiry?->course_id == $course->id) || old('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->name }} @if ($course->is_deleted)
                                        (Deleted)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Source <span class="text-danger">*</span></label>
                        <select name="status" class="form-control select">
                            <x-select-options :items="['pending', 'resolved', 'contacted', 'follow_up', 'not_interested', 'other']" :selected="$is_update ? $inquiry?->status : ''" />
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Due Date</label>
                        <input type="text" name="due_date" class="form-control datepicker"
                            value="{{ $is_update && $inquiry?->due_date ? $inquiry->due_date : old('due_date') }}">
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>Note</label>
                        <textarea name="note" class="form-control" rows="4">
                            {{ $is_update ? $inquiry->note : old('note') }}
                        </textarea>
                    </div>
                </div>
            </div>

            {{-- @if ($is_update == false) --}}
            <button type="submit" class="btn btn-success">
                @if ($is_update)
                    Update
                @else
                    Save
                @endif Inquiry
            </button>
            {{-- @endif --}}
        </form>
    </div>
</div>
