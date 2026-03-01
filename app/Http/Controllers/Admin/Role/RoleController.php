<?php

namespace App\Http\Controllers\Admin\Role;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        // dd("here");
        $permissions = Permission::all();
        return view('admin.access.roles_create', compact('permissions'));
    }

    public function store(Request $request)
    {
            $request->validate([
            'name' => 'required|unique:roles,name'
        ]);
        $role = Role::create(['name' => $request->name]);

        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }
}
