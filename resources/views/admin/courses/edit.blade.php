@extends('admin.admin_main')

@section('content')
<div class="container-fluid">

<h4>Edit Course</h4>

<form method="POST" action="{{ route('courses.update', $course->id) }}">
@csrf

<div class="form-group">
    <label>Name</label>
    <input type="text" name="name" value="{{ $course->name }}" class="form-control">
</div>

<div class="form-group">
    <label>Fee</label>
    <input type="number" name="fee" value="{{ $course->fee }}" class="form-control">
</div>

<div class="form-group">
    <label>Description</label>
    <input type="text" name="description" value="{{ $course->description }}" class="form-control">
</div>

<button class="btn btn-primary">Update</button>
<a href="{{ route('courses.index') }}" class="btn btn-secondary">Back</a>

</form>
</div>
@endsection
