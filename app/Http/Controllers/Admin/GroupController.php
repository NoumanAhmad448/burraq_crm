<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignInstructorRequest;
use App\Http\Requests\AssignStudentRequest;
use App\Models\Group;
use App\Models\GroupInstructor;
use App\Models\GroupEnrollment;
use App\Models\EnrolledCourse;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\GroupRequest;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::with(['instructors', 'enrolledCourses', 'modules'])->get();

        return view('admin.groups.index', compact('groups'));
    }

    public function restore($id)
    {
        $group = Group::withTrashed()->findOrFail($id);
        $group->restore();

        return redirect()->route('admin.groups.trashed')
            ->with('success', 'Group restored successfully');
    }
    public function assignInstructor(AssignInstructorRequest $request, Group $group)
    {
        $instructors = $request->input('instructors', []);
        $group->instructors()->sync($instructors);

        return redirect()->back()->with('success', 'Instructors assigned successfully.');
    }

    public function assignStudent(AssignStudentRequest $request, Group $group)
    {
        $enrolledCourseId = $request->input('enrolled_course_id');
        GroupEnrollment::updateOrCreate([
            'group_id' => $group->id,
            'crm_enrolled_course_id' => $enrolledCourseId,
        ]);

        return redirect()->back()->with('success', 'Student assigned successfully.');
    }


    public function create()
    {
        return view('admin.groups.create');
    }

    public function store(GroupRequest $request)
    {
        Group::create($request->validated());
        return redirect()->route('admin.groups.index')->with('success', 'Group created successfully');
    }

    public function edit(Group $group)
    {
        return view('admin.groups.edit', compact('group'));
    }

    public function update(GroupRequest $request, Group $group)
    {
        $group->update($request->validated());
        return redirect()->route('admin.groups.index')->with('success', 'Group updated successfully');
    }

    public function destroy(Group $group)
    {
        $group->delete(); // Soft delete
        return redirect()->route('admin.groups.index')->with('success', 'Group deleted successfully');
    }
    public function trashed()
    {
        // Only soft deleted groups
        $groups = Group::onlyTrashed()->get();

        return view('admin.groups.trashed', compact('groups'));
    }
}
