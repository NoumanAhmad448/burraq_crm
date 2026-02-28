@extends('admin.admin_main')

@section('content')

<div class="card">
    <div class="card-header">
        <h4>Add New Role</h4>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.roles.store') }}">
            @csrf

            <div class="form-group">
                <label>Role Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">
                <i class="fa fa-save"></i> Save Role
            </button>
        </form>
    </div>
</div>

@endsection