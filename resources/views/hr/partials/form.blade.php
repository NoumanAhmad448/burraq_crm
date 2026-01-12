@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
{{-- write a logic to display the success message belwo --}}
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="card-body">
    <div class="form-group">
        <label for="name">Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="name" name="name"
            value="{{ old('name', $user->name ?? '') }}" required>
    </div>

    <div class="form-group">
        <label for="email">Email <span class="text-danger">*</span></label>
        <input type="email" class="form-control" id="email" name="email"
            value="{{ old('email', $user->email ?? '') }}" required>
    </div>

    @if (!isset($user))
        <div class="form-group">
            <label for="password">Password <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                required>
        </div>
    @endif

    <div class="form-group">
        <label for="mobile_no">Mobile No <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="mobile_no" name="mobile_no"
            value="{{ old('mobile_no', $user->profile->mobile_no ?? '') }}" required>
    </div>

    <div class="form-group">
        <label for="father_name">Father Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="father_name" name="father_name"
            value="{{ old('father_name', $user->profile->father_name ?? '') }}" required>
    </div>

    <div class="form-group">
        <label for="cnic">CNIC <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="cnic" name="cnic"
            value="{{ old('cnic', $user->profile->cnic ?? '') }}" required>
    </div>

    <div class="form-group">
        <label for="guardian_name">Guardian Name</label>
        <input type="text" class="form-control" id="guardian_name" name="guardian_name"
            value="{{ old('guardian_name', $user->profile->guardian_name ?? '') }}">
    </div>

    <div class="form-group">
        <label for="home_address">Home Address</label>
        <textarea class="form-control" id="home_address" name="home_address">{{ old('home_address', $user->profile->home_address ?? '') }}</textarea>
    </div>

    <div class="form-group">
        <label for="photo">User Photo</label>
                @include('file', ['name' => 'photo'])

        @if (isset($user) && $user->profile_photo_path)
            <img src="{{ asset(img_path($user?->profile_photo_path)) }}" alt="User Photo" class="mt-2" width="100">
        @endif
    </div>

    <div class="form-group">
        <label for="cnic_photo">CNIC Photo</label>
        @include('file', ['name' => 'cnic_photo'])

        @if (isset($user) && $user->profile->cnic_photo_path)
            <img src="{{ asset(img_path($user->profile->cnic_photo_path)) }}" alt="CNIC Photo" class="mt-2" width="100">
        @endif
    </div>

    <div class="form-group">
        <label for="resume">Resume</label>
        @include('file', ['name' => 'resume'])
        @if (isset($user) && $user->profile->resume_path)
            <a href="{{ asset(img_path($user->profile->resume_path)) }}" target="_blank">View Resume</a>
        @endif
    </div>

    <div class="form-group">
        <label for="other_document">Other Document</label>
        @include('file', ['name' => 'other_document'])
        @if (isset($user) && $user->profile->other_document_path)
            <a href="{{ asset(img_path($user->profile->other_document_path) ) }}" target="_blank">View Document</a>
        @endif
    </div>
</div>
