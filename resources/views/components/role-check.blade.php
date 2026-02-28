{{-- 
<x-role-check roles="instructor,admission_officer">
<x-role-check roles="hr_role" permissions="print certificate"></x-role-check>
<x-role-check roles="admission_officer" ignore="true">

--}}
@php
    $user = auth()->user();
    $allow = false;

    // dd($user->getRoleNames());
    // Admin and super_admin always allowed
    if ($user->hasAnyRole('admin') || $user->hasRole('super_admin')) {
        $allow = true;
    } else {

        if($ignore) {
            // IGNORE MODE: allow all except listed roles/permissions
            $allow = true;

            if ($roles ?? false) {
                foreach ($roles as $role) {
                    if ($user->hasRole($role)) {
                        $allow = false;
                        break;
                    }
                }
            }

            if (($permissions ?? false) && $allow) {
                foreach ($permissions as $perm) {
                    if ($user->hasPermissionTo($perm)) {
                        $allow = false;
                        break;
                    }
                }
            }

        } else {
            // NORMAL MODE: allow only listed roles/permissions
            if ($roles ?? false) {
                $allow = $user->hasRoleOrSuperAdmin($roles);
            }

            if (!$allow && ($permissions ?? false)) {
                $allow = $user->hasPermissionOrSuperAdmin($permissions);
            }
        }
    }
@endphp

@if($allow)
    {{ $slot }}
@endif