<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Http\Requests\GroupRequest;
use Exception;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::with(['instructors', 'enrolledCourses', 'modules']);
        if(!auth()->user()->is_admin || request()->instructor_id){
            $groups->whereHas("instructors", function($q){
                $q->where("instructor_id", request()->instructor_id ?? auth()->id());
            });
        }

        // \printQuery($groups);
        $groups = $groups->latest()->get();
        
        return view('admin.groups.index', compact('groups'));
    }

    public function restore($id)
    {
        $group = Group::withTrashed()->findOrFail($id);
        $group->restore();

        return redirect()->route('admin.groups.trashed')
            ->with('success', 'Group restored successfully');
    }


    public function create()
    {
        return view('admin.groups.create');
    }

    public function store(GroupRequest $request)
    {
        // dd($request->validated());
        try{
            Group::create($request->validated());
        }
        catch(Exception $e){
            return server_logs([true, $e], [false], true);
        }
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
