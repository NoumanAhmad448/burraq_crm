<div class="col-md-4">
    <label>Name *</label>
    <input type="text" name="name" class="form-control" required placeholder="Student Full Name (Mandatory)"
        value="@if ($is_update) {{ $student->name }}@else{{ old('name') }} @endif">
    <input type="hidden" id="student_id" value="@if ($is_update) {{ $student->id }} @endif" />
</div>

<div class="col-md-4">
    <label>Father Name</label>
    <input type="text" name="father_name" class="form-control" placeholder="Father Name"
        placeholder="12345-1234567-1"
        value="@if ($is_update) {{ $student->father_name }}@else{{ old('father_name') }} @endif">
</div>

<div class="col-md-4">
    <label>CNIC</label>
    <input type="text" id="cnic" name="cnic" class="form-control" placeholder="CNIC"
        value="@if ($is_update) {{ $student->cnic }}@else{{ old('cnic') }} @endif">
    <small class="text-muted">Format: 12345-1234567-1</small>
    <div id="cnic-message" style="display:none;"></div>
</div>
