<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserFormRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsrController extends Controller
{
   public function index(Request $request)
{
    $type = $request->get('type');

    $users = User::where('role', '!=', 'hr')->where("email", "!=", config("auth.bpe"));
    if ($type === 'deleted') {
        // Only show deleted users
        $users->where('is_deleted', 1);
    } else {
        // Active users
        $users->where('is_deleted', 0);
        }
    $is_update = false; // show add user form
    $users = $users->get();

    return view('user.index', compact('users', 'is_update', 'type'));
}
    public function create()
    {
        return view('user.form'); // centralized form
    }

    public function store(UserFormRequest $request)
    {
        $data = $request->validated();

       $data = $this->usrForm($data);

        $user = User::create($data);

        return redirect()->route('admin.user.index')
                        ->with('success', 'User created successfully.');
    }

    private function usrForm($data){

    // Update password only if provided
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
    // Store is_admin or role based on role selection
            if ($data['role'] === 'admin') {
                $data['is_admin'] = 1;
            } else {
                $data['is_admin'] = 0;
                // role stays as admission_officer or hr_role
            }
            return $data;
    }
    public function update(UserFormRequest $request, User $user)
    {
        $data = $request->validated();
        if($request->email == config("auth.bpe")){
            return redirect()->route('admin.user.index')
                        ->with('error', 'This action is forbidden');
        }
        $data = $this->usrForm($data);

        $user->update($data);

        return redirect()->route('admin.user.index')
                        ->with('success', 'User updated successfully.');
    }


    public function edit(Request $request, User $user)
    {
         $type = $request->get('type');
         $is_update = true; // no add user form

        return view('user.index', compact('user', 'is_update', 'type'));
    }


    public function destroy(Request $request, User $user)
    {
        $user->update([
            'is_deleted' => 1,
            'deleted_by' => auth()->id(),
            'deleted_at' => now()
        ]);

        // If user deletes himself → logout
        if (auth()->user()->id === $user->id) {

            Auth::logout();

            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return redirect()->route('login')
                ->with('success', 'Your account has been deleted.');
        }

        return redirect()->route('admin.user.index')
                         ->with('success', 'User deleted successfully.');
    }

    public function logs()
    {
        // Show logs from observer
        $logs = \App\Models\UserLog::latest()->get();
        return view('user.logs', compact('logs'));
    }
}
