        <div class="card mt-3 mb-4">
            <div class="card-body">

                <form method="GET" action="{{ $route ?? route('inquiry_dashboard.index') }}">
                    <div class="row">

                        <!-- COURSE -->
                        {{-- <div class="col-md-3">
                            <label>Course</label>
                            <select name="course_id" class="form-control select">
                                <option value="">-- All Courses --</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}"
                                        {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}

                        <!-- MONTHS -->
                        <div class="col-md-2">
                            <label>Last N Months</label>
                            <select name="last_months" class="form-control">
                                <option value="">-- Select --</option>
                                <option value="1" {{ request('last_months') == 1 ? 'selected' : '' }}>Last 1 Month
                                </option>
                                <option value="3" {{ request('last_months') == 3 ? 'selected' : '' }}>Last 3 Months
                                </option>
                                <option value="5" {{ request('last_months') == 5 ? 'selected' : '' }}>Last 5 Months
                                </option>
                                <option value="7" {{ request('last_months') == 7 ? 'selected' : '' }}>Last 7 Months
                                </option>
                                <option value="9" {{ request('last_months') == 9 ? 'selected' : '' }}>Last 9 Months
                                </option>
                                <option value="12" {{ request('last_months') == 12 ? 'selected' : '' }}>Last 12
                                    Months</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label>Select Month</label>
                            <select name="month" class="form-control">
                                <option value="">-- Select --</option>
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                        {{ \App\Classes\LyskillsCarbon::create()->month($m)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>


                        <!-- YEAR -->
                        <div class="col-md-2">
                            <label>Year</label>
                            <select name="year" class="form-control">
                                <option value="">-- Select Year --</option>
                                @for ($y = 2022; $y <= 2032; $y++)
                                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <!-- START DATE -->
                        <div class="col-md-2">
                            <label>Start Date</label>
                            <input type="text" name="start_date" class="form-control datepicker"
                                value="{{ request('start_date') }}">
                        </div>

                        <!-- END DATE -->
                        <div class="col-md-2">
                            <label>End Date</label>
                            <input type="text" name="end_date" class="form-control datepicker"
                                value="{{ request('end_date') }}">
                        </div>

                        <div class="col-md-1 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-filter"></i>
                            </button>
                        </div>

                    </div>
                </form>

            </div>
        </div>
