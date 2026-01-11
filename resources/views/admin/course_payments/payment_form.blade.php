  <h3>
      @if ($is_update)
          Update
      @else
          Add
      @endif Payment
  </h3>

  @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if ($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif
  <form action="{{ route('course_payments.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="form-group">
          <hr />
          <h3>Enrolled Course</h3>
          <p> {{ $enrolledCourse->course->name ?? 'N/A' }} </p>
          <hr />
          <input type="hidden" name="enrolled_course_id" class="form-control"
              placeholder="Enter Enrolled Course ID if exists" value="{{ $enrolled_course_id }}" required>
      </div>

      <div class="form-group">
          <label>Student</label>
          <select name="student_id" class="form-control" required>
              <option value="">Select Student</option>
              @foreach ($students as $student)
                  <option value="{{ $student->id }}" @if (old('student_id') == $student->id || $student_id == $student->id) selected @endif>
                      {{ $student->name }}</option>
              @endforeach
          </select>
          @if ($is_update)
              <input type="hidden" name="payment_id" value="{{ $payment->id }}">
          @endif
      </div>


      <div class="form-group">
          <label>Paid Amount</label>
          <input type="text" step="0.01" name="paid_amount" class="form-control" required
              value="{{ old('paid_amount', $is_update ? (int) $payment->paid_amount : '') }}">
      </div>

      {{-- <div class="form-group">
            <label>Payment Method</label>
            <select name="payment_method" class="form-control" required>
                <option value="cash">Cash</option>
                <option value="online">Online</option>
            </select>
        </div> --}}

      <div class="form-group">
          <label>Payment Slip (Optional)</label>
          <label class="file-upload-card">

              <input type="file" name="payment_slip" class="form-control" hidden>
              <div class="upload-content">
                  <i class="fa fa-cloud-upload"></i>
                  <p>Click to upload</p>
                  <small>PDF, JPG, PNG (Max 5MB)</small>
              </div>
          </label>
          @if ($is_update && $payment->payment_slip_path)
              <div class="mt-2">
                  <a href="{{ asset(img_path($payment->payment_slip_path)) }}" target="_blank">View Existing
                      Slip</a>
              </div>
          @endif
      </div>

      <button type="submit" class="btn btn-primary">
          @if ($is_update)
              Update
          @else
              Add
          @endif Payment
      </button>
  </form>
