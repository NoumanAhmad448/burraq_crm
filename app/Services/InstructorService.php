<?php

namespace App\Services;

class InstructorService{

    public static function get(){
        return  \App\Models\User::where('role', config('setting.roles.instructor'))->get();
    }
}

