<form method="POST" action="{{ route('courses.store') }}">
    @csrf

    <div class="row">
        <div class="col-md-4">
            <input name="name" class="form-control" placeholder="Course Name">
        </div>
        <div class="col-md-4">
            <input name="fee" class="form-control" placeholder="Fee">
        </div>
        <div class="col-md-4">
            <button class="btn btn-primary">Save</button>
        </div>
    </div>
</form>
