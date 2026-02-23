
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
                @include("admin.students.first_row")
                @include("admin.students.second_row")
                @include("admin.students.thrid_row")
                @include("admin.students.four_row")
            </div>

            {{-- ================= COURSES ================= --}}
            <hr>

           @include('admin.students.course_table')


            {{-- ================= CHECKBOX OPTIONS ================= --}}
            <hr>
            
            @include("admin.students.course_options")

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



@include('admin.students.course_js')
@include('admin.students.student_js')
{{-- ================= END CREATE STUDENT FORM ================= --}}