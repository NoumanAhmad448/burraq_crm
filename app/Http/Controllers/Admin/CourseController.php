<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::where('is_deleted', 0)->get();
        return view('admin.courses.index', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'fee'  => 'required|numeric|min:1'
        ]);

        Course::create($request->only('name', 'fee', 'description'));

        return back()->with('success', 'Course added');
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'fee'  => 'required|numeric|min:1'
        ]);

        Course::where('id', $id)->update($request->only('name', 'fee', 'description'));

        return redirect()->route('courses.index')->with('success', 'Course updated');
    }

    public function delete($id)
    {
        Course::where('id', $id)->update(['is_deleted' => 1]);
        return back()->with('success', 'Course deleted');
    }
}
