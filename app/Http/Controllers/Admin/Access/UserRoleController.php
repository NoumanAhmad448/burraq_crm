<?php

namespace App\Http\Controllers\Admin\Access;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;

class UserRoleController
{
    public function index()
    {
        $users = User::with('roles', 'permissions')->get();
        $roles = Role::all();
        $permissions = Permission::all();

        return view('admin.access.assign_user_role', compact('users', 'roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        // Assign Role
        $user->syncRoles([$request->role]);

        // Optional direct permissions
        if ($request->permissions) {
            $user->syncPermissions($request->permissions);
        }

        return back()->with('success', 'User role & permissions updated.');
    }
}
