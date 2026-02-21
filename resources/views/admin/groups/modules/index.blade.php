@extends('admin.admin_main')

@section('page-css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="container">
            <a class="btn btn-lg btn-success" href="{{ route("admin.groups.index") }}">Back To Group Module</a>

    <h3>Modules for Group: {{ $group->group_name }}</h3>

    @include('messages') {{-- Include success/error messages --}}

    {{-- Add Module Form --}}
    <div class="card mb-4">
        <div class="card-header">Add New Module</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.group.modules.store', $group->id) }}">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Module Name</label>
                        <input type="text" name="module" class="form-control" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Completed?</label>
                        <select name="progress_pct" class="form-control">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3 align-self-end">
                        <button type="submit" class="btn btn-success">Add Module</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Module Listing --}}
    <div class="card">
        <div class="card-header">Modules List</div>
        <div class="card-body">
            <table id="crm_group_modules" class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Module Name</th>
                        <th>Completed</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($modules as $module)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $module->module }}</td>
                        <td>{{ $module->progress_pct ? 'Yes' : 'No' }}</td>
                        <td>
                            {{-- Edit Form (inline) --}}
                            <form method="POST" action="{{ route('admin.group.modules.update', [$group->id, $module->id]) }}" style="display:inline-block;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="module" value="{{ $module->module }}">
                                <select name="progress_pct" class="form-control d-inline-block" style="width:auto; display:inline-block;">
                                    <option value="0" {{ !$module->progress_pct ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ $module->progress_pct ? 'selected' : '' }}>Yes</option>
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm">Update</button>
                            </form>

                            {{-- Delete Form --}}
                            <x-admin>
                            <form method="POST" action="{{ route('admin.group.modules.destroy', [$group->id, $module->id]) }}" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this module?')">
                                    Delete
                                </button>
                            </form>
                                <a href="{{ route('admin.logs.modules', $module->id) }}"
                                    class="btn btn-primary btn-sm ml-2">Logs</a>

                            </x-admin>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('page-js')
<script>
    $(document).ready(function() {
        new simpleDatatables.DataTable("#crm_group_modules", {
            searchable: true,
            perPage: 10
        });
    });
</script>
@endsection