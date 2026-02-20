@extends('admin.admin_main')

@section('content')
<div class="container">
    <h3>{{ isset($group) ? 'Edit Group' : 'Create Group' }}</h3>

    <form method="POST" action="{{ isset($group) ? route('admin.groups.update', $group->id) : route('admin.groups.store') }}">
        @csrf
        @if(isset($group)) @method('PUT') @endif

        <div class="form-group">
            <label>Group Name</label>
            <input type="text" name="group_name" class="form-control" value="{{ old('group_name', $group->group_name ?? '') }}" required>
        </div>

        <div class="form-group">
            <label>Timing</label>
            <input type="text" name="timing" class="form-control" value="{{ old('timing', $group->timing ?? '') }}">
        </div>

        <button type="submit" class="btn btn-success mt-2">{{ isset($group) ? 'Update' : 'Create' }}</button>
    </form>
</div>
@endsection