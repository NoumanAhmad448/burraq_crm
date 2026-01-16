<div class="card">
    @include('messages')

    <div class="card-header">
        <h3>{{ isset($user) && $is_update ? 'Edit User' : 'Create User' }}</h3>
    </div>
    <div class="card-body">
        @if (isset($user) && $is_update)
            <form action="{{ route('admin.user.update', $user?->id) }}" method="POST">
                @method('PUT')
            @else
                <form action="{{ route('admin.user.store') }}" method="POST">
        @endif
        @csrf

        {{-- Name --}}
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="name" value="{{ old('name', $user?->name ?? '') }}"
                required>
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        {{-- Email --}}
        <div class="form-group mt-2">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" value="{{ old('email', $user?->email ?? '') }}"
                required>
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        {{-- Password --}}
        <div class="form-group mt-2">
            <label for="password">{{ isset($user) & $is_update ? 'Change Password (optional)' : 'Password' }}</label>
            <input type="password" class="form-control" name="password" {{ isset($user) ? '' : 'required' }}>
            @error('password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        {{-- Role --}}
        <div class="form-group mt-2">
            <label for="role">Role</label>
            <select name="role" class="form-control" required>
                <option value="admin"
                    {{ old('role', $user?->is_admin ?? 0 ? 'admin' : $user?->role ?? '') == 'admin' ? 'selected' : '' }}>
                    Admin</option>
                <option value="admission_officer"
                    {{ old('role', $user?->role ?? '') == 'admission_officer' ? 'selected' : '' }}>Admission Officer
                </option>
                <option value="hr_role" {{ old('role', $user?->role ?? '') == 'hr_role' ? 'selected' : '' }}>HR Manager
                <option value="print_certificate" {{ old('role', $user?->role ?? '') == 'print_certificate' ? 'selected' : '' }}>Print Certificate
                </option>
            </select>
            @error('role')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        {{-- Submit --}}
        <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary">
                {{ isset($user) & $is_update ? 'Update User' : 'Create User' }}
            </button>
        </div>
        </form>
    </div>
</div>
