<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans; }
    </style>
</head>
<body>

<h2>Burraq Engineering</h2>

<p><strong>Name:</strong> {{ $student->name }}</p>
<p><strong>Father Name:</strong> {{ $student->father_name }}</p>
<p><strong>CNIC:</strong> {{ $student->cnic }}</p>
<p><strong>Mobile:</strong> {{ $student->mobile }}</p>

<hr>

<h4>Enrolled Courses</h4>

<ul>
@foreach($student->enrolledCourses as $course)
    <li>{{ $course->course->name }}</li>
@endforeach
</ul>

</body>
</html>
