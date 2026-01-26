
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
    @include('messages')

    <div class="card-body">
        <form method="POST" id="form_submisssion"
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
                    <input type="text" name="name" class="form-control" required placeholder="Student Full Name (Mandatory)"
                        value="@if($is_update) {{ $student->name }}@else{{ old('name')}}@endif">
                </div>

                <div class="col-md-4">
                    <label>Father Name *</label>
                    <input type="text" name="father_name" class="form-control" required placeholder="Father Name (Mandatory)"
                        value="@if($is_update) {{ $student->father_name }}@else{{ old('father_name')}}@endif">
                </div>

                <div class="col-md-4">
                    <label>CNIC *</label>
                    <input type="text" name="cnic" class="form-control" placeholder="CNIC (Mandatory)"
                        value="@if($is_update) {{ $student->cnic }}@else{{ old('cnic')}}@endif">
                </div>

                <div class="col-md-4 mt-2">
                    <label>Mobile *</label>
                    <input type="text" name="mobile" class="form-control" placeholder="Mobile No. (Mandatory)"
                        value="@if($is_update) {{ $student->mobile }}@else{{ old('mobile')}}@endif">
                </div>

                <div class="col-md-4 mt-2">
                    <label>Email*</label>
                    <input type="email" name="email" class="form-control" placeholder="Email Address (Mandatory)"
                        value="@if($is_update) {{ $student->email }}@else{{ old('email')}}@endif">
                </div>

                   @php
                        $selectedMethod = old(
                            'payment_method',
                            ($is_update && $student) ? ($student?->enrolledCourses()->first()->payments()?->first()?->payment_method ?? null) : null
                        );
                        $paymentDate = old(
                            'payment_date',
                            ($is_update && $student)
                                ? $student?->enrolledCourses()->first()->payments()?->first()?->payment_date
                                : ""
                        );

                        $payment_slip_path = $is_update && $student ? $student?->enrolledCourses()->first()->payments()?->first()?->payment_slip_path : "";
                    @endphp
                <div class="col-md-4 mt-2">
                    <label>Payment Method</label>

                    <select name="payment_method" class="form-control">
                        <option value="">Select Method</option>

                        <option value="cash"      {{ $selectedMethod === 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="bank"      {{ $selectedMethod === 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="online"    {{ $selectedMethod === 'online' ? 'selected' : '' }}>Online</option>
                        <option value="easypaisa" {{ $selectedMethod === 'easypaisa' ? 'selected' : '' }}>Easypaisa</option>
                        <option value="jazzcash"  {{ $selectedMethod === 'jazzcash' ? 'selected' : '' }}>Jazzcash</option>
                    </select>
                </div>
                <div class="col-md-4 mt-2">

                    <div class="form-group">
                        <label>Payment Date</label>
                        <input type="date"
                            name="payment_date"
                            class="form-control datepicker"
                            value="{{ $paymentDate }}">
                    </div>
                </div>

                {{-- <div class="col-md-4 mt-2">
                    <label>Admission Date</label>
                    <input type="text" name="admission_date" class="form-control datepicker"
                        value="@if($is_update){{ old('admission_date', $student?->admission_date)}}@endif">
                </div>
                <div class="col-md-4 mt-2">
                    <label>Due Date </label>
                    <input type="text" name="due_date" class="form-control datepicker"
                        value="@if($is_update){{ old('due_date', dateFormat($student->admission_date))}}@endif">
                </div>

                <div class="col-md-4 mt-2">
                    <label>Total Fee </label>
                    <input type="text" name="total_fee" class="form-control" required step="any"
                        value="@if ($is_update) {{ (int) $student->total_fee }}@else{{ old('total_fee') }} @endif">
                </div>
                <div class="col-md-4 mt-2">
                    <label>Paid Fee </label>
                    <input type="text" name="paid_fee" class="form-control" required step="0.01"
                        value="@if ($is_update) {{ (int) $student->paid_fee }}@else{{ old('paid_fee') }} @endif">
                </div> --}}
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
                        <a href="{{ asset(img_path($payment_slip_path)) }}" target="_blank" class="text-primary underscore">View Current
                            Slip</a>
                    @endif
                </div>
            </div>

            {{-- ================= COURSES ================= --}}
            <hr>
            <strong>Enroll Courses</strong>

            <table class="table table-bordered mt-2 courses" id="course_table">
                <thead class="thead-light">
                    <tr>
                        <th>Select</th>
                        <th >Course</th>
                        <th >Total Fee</th>
                        <th >Paid Amount</th>
                        <th >Admission Date</th>
                        <th >Due Date</th>

                    </tr>
                </thead>

                <tbody>
                    @foreach ($all_courses as $course)
                        @php
                            $enrolledCourse = null;

                            if ($is_update) {
                                $enrolledCourse = $student?->enrolledCourses()
                                    ->where('course_id', $course->id)
                                    ->where('student_id', $student?->id)
                                    ->where('is_deleted', 0)
                                    ->first();
                            }
                            if($enrolledCourse && $enrolledCourse?->due_date){
                                $enrolledCourse->due_date = dateFormat($enrolledCourse->due_date);
                            }
                            if($enrolledCourse && $enrolledCourse?->admission_date){
                                $enrolledCourse->admission_date = dateFormat($enrolledCourse->admission_date);
                            }
                        @endphp

                        <tr class="course-row">
                            {{-- Select --}}
                            <td class="text-center">
                                <input type="checkbox" name="courses[{{ $course->id }}][selected]"
                                @if (old('courses.' . $course->id . '.selected') || $enrolledCourse) checked @endif
                                >
                                @if ($is_update && $enrolledCourse)
                                    <input type="hidden" name="courses[{{ $course->id }}][CEId]"
                                        value="{{ $enrolledCourse->id }}">
                                @endif
                                <input type="hidden" name="courses[{{ $course->id }}][course_id]"
                                    value="{{ $course->id }}">
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
                                    class="form-control total-fee" placeholder="Course Fee"
                                    value="{{ old('courses['.$course->id.'][total_fee]', $enrolledCourse?->total_fee > 0 ? (int) $enrolledCourse?->total_fee : '') }}">
                            </td>

                            {{-- Paid Amount --}}
                            <td>
                                @if ($is_update && $enrolledCourse?->payments?->first())
                                    <input type="hidden" name="courses[{{ $course->id }}][payId]"
                                        value="{{ $enrolledCourse?->payments?->first()->id }}">
                                @endif
{{-- {{dump(dateFormat($course?->admission_date))}} --}}
                                <input type="text" name="courses[{{ $course->id }}][paid_amount]"
                                    class="form-control paid-amount" placeholder="Paid"
                                    value="{{ old('courses['.$course->id.'][paid_amount]', $enrolledCourse?->payments?->first()->paid_amount > 0 ? (int) $enrolledCourse?->payments?->first()->paid_amount : '') }}">
                            </td>
                            <td>
                                <input type="text" name="courses[{{ $course->id }}][admission_date]" class="form-control datepicker" placeholder="Admission Date"
                                    value="{{ old('courses['.$course->id.'][admission_date]', $enrolledCourse?->admission_date) }}">
                            </td>
                            <td>
                                <input type="text" name="courses[{{ $course->id }}][due_date]" class="form-control datepicker" placeholder="due Date"
                                    value="{{old('courses['.$course->id.'][due_date]', $enrolledCourse?->due_date) }}">
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
$(document).ready(function() {
    new DataTable('.courses', {
        language: {
                    search: '',
                    searchPlaceholder: 'Search Courses ...'
                },
    pageLength: 5,
    columnDefs: [
        { targets: 0, width: '5%' },   // first column (e.g., checkbox)
        { targets: 1, width: '15%' },  // second column
        { targets: 2, width: '15%' },   // third column
        { targets: 3, width: '15%' },   // fourth column
        { targets: 4, width: '15%' },   // fifth column
        { targets: 5, width: '15%' },   // sixth column
        // other columns can auto-size
    ],
});

});
</script>
<script>
    $(document).ready(function() {

        setTimeout(function() {
            console.log($(".dtsp-emptyMessage").first());
            $(".dtsp-emptyMessage").first().hide(); // Hide 'No panes to display' message
        }, 5000);
    });

    $('#form_submisssion').on('submit', function(e) {
        e.preventDefault(); // prevent the default submit until we are ready
            var form = this;
        var table = new DataTable('#course_table');

            // Save the state of all checkboxes before changing pagination
            var checkboxStates = {};
            table.$('input[type="checkbox"]').each(function() {
                var name = $(this).attr('name');
                checkboxStates[name] = $(this).prop('checked');
            });

        // Store current pagination length
        var originalPageLength = table.page.len();

        // Show all rows temporarily
        table.page.len(-1).draw();
        // Wait a tick for the browser to render all rows
        setTimeout(function() {

            // Restore checkbox states
        table.$('input[type="checkbox"]').each(function() {
            var name = $(this).attr('name');
            if (checkboxStates.hasOwnProperty(name)) {
                $(this).prop('checked', checkboxStates[name]);
            }
        });
            // Submit the form normally
            form.submit();

        // Restore original pagination (optional, if page reloads it won't matter)
        table.page.len(originalPageLength).draw();
    }, 50);

    });

</script>
{{-- ================= END CREATE STUDENT FORM ================= --}}