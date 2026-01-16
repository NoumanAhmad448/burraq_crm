<?php
namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthenticateUser
{
    public function __invoke(Request $request): ?User
    {

    // dd($request->all());
        // ❌ Block deleted users
        $user = User::where('email', $request->email)
            ->first();

        // dd($user);
        // 🔐 Custom rules
        if ($user->is_deleted == 1) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'Your account has been disabled.',
            ]);
        }

        return $user;
    }

}
