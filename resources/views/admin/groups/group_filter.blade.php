<div class="row mb-3 mt-3 mr-5">
    <div class="col-md-12 d-flex justify-content-end">
        <form method="GET" action="{{ route('admin.groups.index') }}" class="form-inline justify-content-end mb-3">
            <x-admin>
                <div class="form-group mr-2">
                    <select name="instructor_id" class="form-control select">
                        <option value=""> -- Select Instructor -- </option>
                        @foreach (\App\Services\InstructorService::get() as $user)
                            <option value="{{ $user->id }}"
                                {{ request()->instructor_id == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} -- {{ $user->email }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </x-admin>
            <button type="submit" class="btn btn-primary btn-sm mb-0">
                Filter
            </button>

        </form>
    </div>
</div>
