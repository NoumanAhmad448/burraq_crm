<?php

namespace App\Http\Controllers;

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

class EnrollmentController extends Controller
{

    public function removeStudent(Group $group, EnrolledCourse $student)
    {
        $group->enrolledCourses()
            ->updateExistingPivot($student->id, [
                'deleted_at' => now()
            ]);

        return back()->with('success', 'Student removed successfully.');
    }
    public function assignInstructor(AssignInstructorRequest $request)
    {
        // dd($request->all());
        $group = Group::find($request->input("group_id"));
        $instructors = $request->input('instructors', []);

        $existing = $group->instructors()->pluck('instructor_id')->toArray();

        $toAttach = array_diff($instructors, $existing);
        $toDetach = array_diff($existing, $instructors);

        // Create manually (fires observer)
        foreach ($toAttach as $id) {
            GroupInstructor::create([
                'group_id' => $group->id,
                'instructor_id' => $id
            ]);
        }

        // Delete manually (fires observer)
        GroupInstructor::where('group_id', $group->id)
            ->whereIn('instructor_id', $toDetach)
            ->delete();

        return redirect()->back()->with('success', 'Instructors assigned successfully.');
    }

    public function assignStudent(AssignStudentRequest $request)
    {
        $group = Group::find($request->input("group_id"));
        $enrolledCourseId = $request->input('enrolled_course_id');

        $enrollment = GroupEnrollment::withTrashed()
            ->where('group_id', $group->id)
            ->where('crm_enrolled_course_id', $enrolledCourseId)
            ->first();

        if ($enrollment) {

            if ($enrollment->trashed()) {
                $enrollment->restore();
            }

        } else {

            GroupEnrollment::create([
                'group_id' => $group->id,
                'crm_enrolled_course_id' => $enrolledCourseId,
            ]);
        }

        return redirect()->back()->with('success', 'Student assigned successfully.');
    }

    public function students(Group $group)
    {
        $students = $group->enrolledCourses()->get();
        $groups = Group::withTrashed()->get();
        return view('admin.groups.students', compact("groups", 'group', 'students'));
    }

    public function updateStudents(Request $request, Group $group)
    {
        $studentIds = $request->student_ids ?? [];

        $existing = $group->enrolledCourses()->pluck('crm_enrolled_course_id')->toArray();

        $toAttach = array_diff($studentIds, $existing);
        $toDetach = array_diff($existing, $studentIds);

        // Create manually (fires observer)
        foreach ($toAttach as $id) {
            GroupEnrollment::create([
                'group_id' => $group->id,
                'crm_enrolled_course_id' => $id
            ]);
        }

        // Delete manually (fires observer)
        GroupEnrollment::where('group_id', $group->id)
            ->whereIn('crm_enrolled_course_id', $toDetach)
            ->delete();

        return back()->with('success', 'Students updated successfully');
    }
}
