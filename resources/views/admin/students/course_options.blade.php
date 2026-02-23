<div class="form-check">
    <input class="form-check-input" type="checkbox" name="print" value="1">
    <label class="form-check-label">Print Student</label>
</div>
@if ($is_update == false)
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="continue_add" value="1" checked>
        <label class="form-check-label">Continue Add</label>
    </div>
@else
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('students.print', $student->id) }}" class="btn btn-secondary">
            Print Student
        </a>
    </div>
@endif
