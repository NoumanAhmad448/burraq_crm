<!-- Assign Instructor Form -->
<form method="POST" action="{{ route('admin.group.assign_instructor', $group->id ?? 0) }}">
    @csrf
    @include('admin.groups.group_listing')
    <label>Assign Instructors</label>
    {{-- @dd(App\Models\User::where('role', "instructor")->get()) --}}
    <select name="instructors[]" multiple class="form-control select">
        @foreach (App\Models\User::where('role', config('setting.roles.instructor'))->get() as $user)
            <option value="{{ $user->id }}">{{ $user->name }} -- {{ $user->email }} </option>
        @endforeach
    </select>
    <button class="btn btn-success mt-2">Assign</button>
</form>
