<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class RolesAndUserRoleSeeder extends Seeder
{
    public function run()
    {
        // 1️⃣ Create all roles from config
        $rolesConfig = config('crm_roles.roles');

        foreach ($rolesConfig as $key => $roleData) {
            Role::firstOrCreate(['name' => $key]);
        }

        // 2️⃣ Assign roles to users
        $users = User::all();

        foreach ($users as $user) {

            // Admin user
            if ($user->is_admin) {
                $user->assignRole('admin');
                continue;
            }
            if ($user->is_super_admin) {
                $user->assignRole('super_admin');
                continue;
            }

            // Assign role from `role` column if exists
            if (!empty($user->role) && Role::where('name', $user->role)->exists()) {
                $user->assignRole($user->role);
            }
        }
    }
}