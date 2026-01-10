@extends('admin.admin_main')

@section('content')
<div class="container-fluid">

<h4>Courses</h4>

<form method="POST" action="{{ route('courses.store') }}" class="mb-4">
@csrf
<div class="row">
    <div class="col-md-4">
        <input type="text" name="name" class="form-control" placeholder="Course Name" required>
    </div>
    <div class="col-md-3">
        <input type="number" name="fee" class="form-control" placeholder="Fee" required>
    </div>
    <div class="col-md-4">
        <input type="text" name="description" class="form-control" placeholder="Description">
    </div>
    <div class="col-md-1">
        <button class="btn btn-primary">Add</button>
    </div>
</div>
</form>

<table class="table table-bordered" id="crm_students">
<thead>
<tr>
    <th>Name</th>
    <th>Fee</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
@foreach($courses as $course)
<tr>
    <td>{{ $course->name }}</td>
    <td>{{ $course->fee }}</td>
    <td>
        <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-sm btn-info">Edit</a>

        @if(auth()->user()->is_admin)
        <form method="POST" action="{{ route('courses.delete', $course->id) }}" style="display:inline;">
            @csrf
            <button class="btn btn-sm btn-danger">Delete</button>
        </form>
        @endif
    </td>
</tr>
@endforeach
</tbody>
</table>

</div>
@endsection
@section('page-js')

<script>
        $(document).ready(function() {
            new simpleDatatables.DataTable("#crm_students", {
                searchable: true,
                perPage: 10
            });

        });
    </script>
@endsection