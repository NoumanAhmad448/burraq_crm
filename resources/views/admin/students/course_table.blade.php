            <strong>Enroll Courses</strong>

            <table class="table table-bordered mt-2 courses" id="course_table">
                <thead class="thead-light">
                    <tr>
                        <th>Select</th>
                        <th>Course</th>
                        <th>Total Fee</th>
                        <th>Paid Amount</th>
                        <th>Admission Date</th>
                        <th>Due Date</th>

                    </tr>
                </thead>

                <tbody>
                    @foreach ($all_courses as $course)
                        @php
                            $enrolledCourse = null;

                            if ($is_update) {
                                $enrolledCourse = $student
                                    ?->enrolledCourses()
                                    ->where('course_id', $course->id)
                                    ->where('student_id', $student?->id)
                                    ->where('is_deleted', 0)
                                    ->first();
                            }
                            if ($enrolledCourse && $enrolledCourse?->due_date) {
                                $enrolledCourse->due_date = dateFormat($enrolledCourse->due_date);
                            }
                            if ($enrolledCourse && $enrolledCourse?->admission_date) {
                                $enrolledCourse->admission_date = dateFormat($enrolledCourse->admission_date);
                            }
                        @endphp

                        <tr class="course-row">
                            {{-- Select --}}
                            <td class="text-center">
                                <input type="checkbox" name="courses[{{ $course->id }}][selected]"
                                    @if (old('courses.' . $course->id . '.selected') || $enrolledCourse) checked @endif>
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
                                    value="{{ old('courses[' . $course->id . '][total_fee]', $enrolledCourse?->total_fee > 0 ? (int) $enrolledCourse?->total_fee : '') }}">
                            </td>

                            {{-- Paid Amount --}}
                            <td>
                                @if ($is_update && $enrolledCourse?->payments?->first())
                                    <input type="hidden" name="courses[{{ $course->id }}][payId]"
                                        value="{{ $enrolledCourse?->payments?->first()->id }}">
                                @endif
                                <input type="text" name="courses[{{ $course->id }}][paid_amount]"
                                    class="form-control paid-amount" placeholder="Paid"
                                    value="{{ old('courses[' . $course->id . '][paid_amount]', $enrolledCourse?->payments?->first()->paid_amount > 0 ? (int) $enrolledCourse?->payments?->first()->paid_amount : '') }}">
                            </td>
                            <td>
                                <input type="text" name="courses[{{ $course->id }}][admission_date]"
                                    class="form-control datepicker" placeholder="Admission Date"
                                    value="{{ old('courses[' . $course->id . '][admission_date]', $enrolledCourse?->admission_date) }}">
                            </td>
                            <td>
                                <input type="text" name="courses[{{ $course->id }}][due_date]"
                                    class="form-control datepicker" placeholder="Due Date"
                                    value="{{ old('courses[' . $course->id . '][due_date]', $enrolledCourse?->due_date) }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
