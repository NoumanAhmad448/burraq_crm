<?php

namespace App\Traits;

trait UserTrait
{
    public function hasRoleOrSuperAdmin($roles, $ignoreAdmin = true)
    {
        // Admin and super_admin always allowed
        if (($ignoreAdmin && $this->hasRole('admin')) || $this->hasRole('super_admin')) {
            return true;
        }

        // Accept array or string
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
            return false;
        }

        return $this->hasRole($roles);
    }

    /**
     * Check if user has permission or is admin/super_admin
     *
     * @param string|array $permissions
     * @param bool $ignoreAdmin
     * @return bool
     */
    public function hasPermissionOrSuperAdmin($permissions, $ignoreAdmin = true)
    {
        if (($ignoreAdmin && $this->hasRole('admin')) || $this->hasRole('super_admin')) {
            return true;
        }

        if (is_array($permissions)) {
            foreach ($permissions as $perm) {
                if ($this->hasPermissionTo($perm)) {
                    return true;
                }
            }
            return false;
        }

        return $this->hasPermissionTo($permissions);
    }
}
