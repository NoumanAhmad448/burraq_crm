{{-- ================= CREATE STUDENT FORM ================= --}}
<div class="card mb-4">
    <div class="card-header">
        <strong>
            @if ($is_update)
                Edit Student
            @else
                Create Student
            @endif
        </strong>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- write a logic to display the success message --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card-body">
        <form method="POST"
            action="@if ($is_update) {{ route('students.update', $student->id) }}@else{{ route('students.store') }} @endif"
            enctype="multipart/form-data">
            @csrf
            @method('post')
            @if ($is_update && $student?->photo)
                <div class="row justify-content-center align-items-center mb-4">
                    <img src="{{ asset(img_path($student?->photo)) }}" alt="lyskills" width="100" height="100"
                        class="img-fluid mb-1 rounded-circle shadow-sm img-fluid w-25 h-25" />
                </div>
            @endif
            <div class="row">
                <div class="col-md-4">
                    <label>Name *</label>
                    <input type="text" name="name" class="form-control" required
                        value="@if ($is_update) {{ $student->name }}@else{{ old('name') }} @endif">
                </div>

                <div class="col-md-4">
                    <label>Father Name *</label>
                    <input type="text" name="father_name" class="form-control" required
                        value="@if ($is_update) {{ $student->father_name }}@else{{ old('father_name') }} @endif">
                </div>

                <div class="col-md-4">
                    <label>CNIC *</label>
                    <input type="text" name="cnic" class="form-control"
                        value="@if ($is_update) {{ $student->cnic }}@else{{ old('cnic') }} @endif">
                </div>

                <div class="col-md-4 mt-2">
                    <label>Mobile *</label>
                    <input type="text" name="mobile" class="form-control"
                        value="@if ($is_update) {{ $student->mobile }}@else{{ old('mobile') }} @endif">
                </div>

                <div class="col-md-4 mt-2">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control"
                        value="@if ($is_update) {{ $student->email }}@else{{ old('email') }} @endif">
                </div>


                <div class="col-md-4 mt-2">
                    <label>Admission Date*</label>
                    <input type="text" name="admission_date" class="form-control datepicker"
                        value="@if ($is_update) {{ $student?->admission_date }}@else{{ old('admission_date') }} @endif">
                </div>
                <div class="col-md-4 mt-2">
                    <label>Due Date *</label>
                    <input type="text" name="due_date" class="form-control datepicker"
                        value="@if ($is_update) {{ dateFormat($student->admission_date) }}@else{{ old('due_date') }} @endif">
                </div>

                <div class="col-md-4 mt-2">
                    <label>Total Fee *</label>
                    <input type="text" name="total_fee" class="form-control" required step="any"
                        value="@if ($is_update) {{ (int) $student->total_fee }}@else{{ old('total_fee') }} @endif">
                </div>
                <div class="col-md-4 mt-2">
                    <label>Paid Fee *</label>
                    <input type="text" name="paid_fee" class="form-control" required step="0.01"
                        value="@if ($is_update) {{ (int) $student->paid_fee }}@else{{ old('paid_fee') }} @endif">
                </div>
                <div class="col-md-4 mt-2">
                    <label>Photo</label>
                    {{-- <input type="file" name="photo" class="form-control" value="{{ old('photo') }}"> --}}
                    @include('file', ['name' => 'photo'])
                </div>
                <div class="col-md-4 mt-2">
                    <label>Payment Slip</label>
                    {{-- <input type="file" name="payment_slip_path" class="form-control"
                            value="{{ old('payment_slip_path') }}"> --}}
                    @include('file', ['name' => 'payment_slip_path'])
                    <br />
                    @if ($is_update && $student->payment_slip_path)
                        <a href="{{ asset(img_path($student->payment_slip_path)) }}" target="_blank">View Current
                            Slip</a>
                    @endif
                </div>
            </div>

            {{-- ================= COURSES ================= --}}
            <hr>
            <strong>Enroll Courses</strong>

            <table class="table table-bordered mt-2 courses">
                <thead class="thead-light">
                    <tr>
                        <th style="width:5%">Select</th>
                        <th style="width:35%">Course</th>
                        <th style="width:30%">Total Fee</th>
                        <th style="width:30%">Paid Amount</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($courses as $course)
                        @php
                            $enrolledCourse = null;
                            if ($is_update) {
                                $enrolledCourse = $student?->enrolledCourses
                                    ->where('course_id', $course->id)
                                    ->where('student_id', $student?->id)
                                    ->first();
                            }
                        @endphp

                        <tr>
                            {{-- Select --}}
                            <td class="text-center">
                                <input type="checkbox" name="courses[{{ $course->id }}][selected]"
                                    @if ($enrolledCourse) checked @endif>

                                @if ($is_update && $enrolledCourse)
                                    <input type="hidden" name="courses[{{ $course->id }}][CEId]"
                                        value="{{ $enrolledCourse->id }}">
                                @endif
                            </td>

                            {{-- Course --}}
                            <td>
                                {{ $course->name }} - {{ (int) $course->fee }}
                                @if ($course->is_deleted)
                                    <span class="badge badge-danger ml-2">Deleted</span>
                                @endif
                            </td>

                            {{-- Total Fee --}}
                            <td>
                                <input type="text" name="courses[{{ $course->id }}][total_fee]"
                                    class="form-control" placeholder="Course Fee"
                                    value="{{ old('total_fee', $enrolledCourse?->total_fee > 0 ? (int) $enrolledCourse?->total_fee : '') }}">
                            </td>

                            {{-- Paid Amount --}}
                            <td>
                                @if ($is_update && $enrolledCourse?->payments?->first())
                                    <input type="hidden" name="courses[{{ $course->id }}][payId]"
                                        value="{{ $enrolledCourse->payments->first()->id }}">
                                @endif

                                {{-- {{dump(($enrolledCourse?->payments?->first()?->paid_amount ))}} --}}
                                <input type="text" name="courses[{{ $course->id }}][paid_amount]"
                                    class="form-control" placeholder="Paid"
                                    value="{{ old('paid_amount', $enrolledCourse?->payments?->first()?->paid_amount > 0 ? (int) $enrolledCourse?->payments?->first()?->paid_amount : '') }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>


            {{-- ================= CHECKBOX OPTIONS ================= --}}
            <hr>
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

            <button class="btn btn-primary mt-3">
                @if ($is_update)
                    Update Student
                @else
                    Save Student
                @endif
            </button>
        </form>
    </div>
</div>

<script>
    const dataTable = new simpleDatatables.DataTable(".courses", {
    searchable: true,
    perPage: 5
});

// Store user selections
const userSelections = new Set();

// Clear ALL checkboxes immediately when page changes
function clearAutoSelectedCheckboxes() {
    // Immediately uncheck all checkboxes on page load/change
    document.querySelectorAll('input[name^="courses["][name$="[selected]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    // Also clear any select-all
    const selectAll = document.getElementById('select-all');
    if (selectAll) {
        selectAll.checked = false;
        selectAll.indeterminate = false;
    }
}

// Restore user selections after delay
function restoreUserSelections() {
    // Clear timeout if exists
    if (window.restoreSelectionTimeout) {
        clearTimeout(window.restoreSelectionTimeout);
    }
    
    // Step 1: Immediately clear all auto-selected checkboxes
    clearAutoSelectedCheckboxes();
    
    // Step 2: Wait 1 second and restore user's selections
    window.restoreSelectionTimeout = setTimeout(() => {
        console.log('Restoring user selections...');
        
        document.querySelectorAll('input[name^="courses["][name$="[selected]"]').forEach(checkbox => {
            const key = `${checkbox.name}|${checkbox.value}`;
            
            // Only check if user previously selected it
            if (userSelections.has(key)) {
                checkbox.checked = true;
            }
        });
        
        updateSelectAllState();
        
    }, 1000); // 1 second delay
}

// Track user checkbox selections
document.addEventListener('change', function(e) {
    if (e.target.name && e.target.name.startsWith('courses[')) {
        const key = `${e.target.name}|${e.target.value}`;
        
        if (e.target.checked) {
            userSelections.add(key);
            console.log('User selected:', key);
        } else {
            userSelections.delete(key);
            console.log('User deselected:', key);
        }
        
        // Save to storage
        localStorage.setItem('userCourseSelections', JSON.stringify(Array.from(userSelections)));
    }
});

// Load saved selections
const saved = localStorage.getItem('userCourseSelections');
if (saved) {
    JSON.parse(saved).forEach(key => userSelections.add(key));
}

// Setup event listeners
dataTable.on('datatable.page', function() {
    console.log('Page changed, clearing auto-selections...');
    restoreUserSelections();
});

dataTable.on('datatable.init', restoreUserSelections);
dataTable.on('datatable.sort', restoreUserSelections);
dataTable.on('datatable.search', restoreUserSelections);

// Initial restoration
setTimeout(restoreUserSelections, 500);
</script>
