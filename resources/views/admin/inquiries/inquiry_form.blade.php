<div class="card">
    <div class="card-header">
        <h5> @if ($is_update) Edit Inquiry @else Create Inquiry @endif</h5>
    </div>

    <div class="card-body">
        <form method="POST" action="@if($is_update) {{ route('inquiries.update', $inquiry?->id) }} @else {{ route('inquiries.store') }} @endif">
            @csrf

            <div class="form-group">
                <label>Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control"
                       value="@if($is_update) {{ $inquiry->name }} @else {{ old('name') }} @endif" required>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control"
                       value="@if($is_update) {{ $inquiry->phone }} @else {{ old('phone') }} @endif">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" class="form-control"
                       value="@if($is_update) {{ $inquiry->email }} @else {{ old('email') }} @endif">
            </div>

            <div class="form-group">
                <label>Interested Course</label>
                <select name="course_id" class="form-control">
                    <option value="">Select Course</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}"
                            {{ ($is_update && ($inquiry?->course_id == $course->id )) || old('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->name }} @if($course->is_deleted) (Deleted) @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="pending" {{ $is_update && $inquiry?->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="resolved" {{ $is_update && $inquiry?->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="other" {{ $is_update && $inquiry?->status == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="form-group">
                <label>Note</label>
                <textarea name="note" class="form-control" rows="4">@if($is_update) {{ $inquiry->note }} @else {{ old('note') }} @endif</textarea>
            </div>

            <button type="submit" class="btn btn-success">
                @if($is_update) Update @else Save @endif Inquiry
            </button>
        </form>
    </div>
</div>