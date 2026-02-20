<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupCourseProgress;
use Illuminate\Http\Request;

class GroupModuleController extends Controller
{
    public function index(Group $group)
    {
        $modules = $group->courseProgress()->get();
        return view('admin.groups.modules.index', compact('group', 'modules'));
    }

    public function store(Request $request, Group $group)
    {
        $request->validate([
            'module' => 'required|string|max:255',
            'progress_pct' => 'nullable|boolean',
        ]);

        $group->courseProgress()->create($request->only(['module','progress_pct']));

        return redirect()->route('admin.group.modules', $group->id)
                         ->with('success', 'Module added successfully');
    }

    public function update(Request $request, Group $group, GroupCourseProgress $module)
    {
        $request->validate([
            'module' => 'required|string|max:255',
            'progress_pct' => 'nullable|boolean',
        ]);

        $module->update($request->only(['module','progress_pct']));

        return redirect()->route('admin.group.modules', $group->id)
                         ->with('success', 'Module updated successfully');
    }

    public function destroy(Group $group, GroupCourseProgress $module)
    {
        $module->delete();
        return redirect()->route('admin.group.modules', $group->id)
                         ->with('success', 'Module deleted successfully');
    }
}