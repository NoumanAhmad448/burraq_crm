<?php

namespace App\View\Components;

use Illuminate\View\Component;

class RoleCheck extends Component
{
    public $roles;
    public $permissions;
    public $ignore;

    public function __construct($roles = null, $permissions = null, $ignore = false)
    {
        $this->roles = is_string($roles) ? explode(',', $roles) : $roles;
        $this->permissions = is_string($permissions) ? explode(',', $permissions) : $permissions;
        $this->ignore = filter_var($ignore, FILTER_VALIDATE_BOOLEAN);
    }

    public function render()
    {
        return view('components.role-check');
    }
}