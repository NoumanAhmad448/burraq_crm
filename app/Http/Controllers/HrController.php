<?php

namespace App\Http\Controllers;

use App\Http\Requests\HrStoreRequest;
use App\Http\Requests\HrUpdateRequest;
use App\Models\User;
use App\Models\Profile;
use App\Models\UserLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class HrController extends Controller
{
    /**
     * HR listing + add form (single page)
     */
    public function index()
    {
        $hrs = User::with('profile')
            ->where('role', 'hr')
            ->orderBy('id', 'desc')
            ->get();

        return view('hr.index', compact('hrs'));
    }

    private function storeFiles($request)
    {
        $profile_photo_path = null;
        $profileData = [];

        if ($request->hasFile('photo')) {
            $profile_photo_path = uploadPhoto($request->file('photo'));
        }

        if ($profile_photo_path) {
            $profile_photo_path = $profile_photo_path;
        }
        if ($request->hasFile('cnic_photo')) {
            $profileData['cnic_photo_path'] = uploadPhoto($request->file('cnic_photo'));
        }

        if ($request->hasFile('resume')) {
            $profileData['resume_path'] = uploadPhoto($request->file('resume'));
        }

        if ($request->hasFile('other_document')) {
            $profileData['other_document_path'] = uploadPhoto($request->file('other_document'));
        }
        return ["profileData" => $profileData, 'profile_photo_path' => $profile_photo_path];
    }
    /**
     * Store HR user + profile
     */
    public function store(HrStoreRequest $request)
    {
        // dd($request->all());
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make(($request->password)),
            'role'      => 'hr',
            'is_admin'  => 0,
        ]);

        $profileData = [
            'mobile_no'      => $request->mobile_no,
            'father_name'    => $request->father_name,
            'cnic'           => $request->cnic,
            'guardian_name'  => $request->guardian_name,
            'home_address'   => $request->home_address,
        ];


        $fileData = $this->storeFiles($request);
        $profileData = array_merge($profileData, $fileData['profileData']);

        if($fileData['profile_photo_path']){
            $user->update(
                [
                    'profile_photo_path' => $fileData['profile_photo_path']
                ]);
        }
        $user->profile()->create($profileData);

        server_logs('HR created', [
            'module' => 'HR',
            'user_id' => auth()->id(),
            'affected_user_id' => $user->id
        ]);

        return redirect()->route('hr.index')
            ->with('success', 'HR added successfully');
    }

    /**
     * Edit HR
     */
    public function edit(User $user)
    {
        // if ($user->role !== 'hr' || !$user->is_admin) {
        //     abort(404);
        // }

        $user->load('profile');
        // dd($user);

        return view('hr.edit', compact('user'));
    }

    /**
     * Update HR user + profile
     */
    public function update(HrUpdateRequest $request, User $user)
    {
        // if ($user->role !== 'hr' || !$user->is_admin) {
        //     abort(404);
        // }

        // dd($request->all());
        $data = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        // dd($data);
        $user_response = $user->update($data);
        // dd($user_response);

        $profileData = [
            'mobile_no'      => $request->mobile_no,
            'father_name'    => $request->father_name,
            'cnic'           => $request->cnic,
            'guardian_name'  => $request->guardian_name,
            'home_address'   => $request->home_address,
        ];

        $fileData = $this->storeFiles($request);
        $profileData = array_merge($profileData, $fileData['profileData']);

        if($fileData['profile_photo_path']){
            $user->update(
                [
                    'profile_photo_path' => $fileData['profile_photo_path']
                ]);
        }

        $user->profile()->update($profileData);

        server_logs('HR updated', [
            'module' => 'HR',
            'user_id' => auth()->id(),
            'affected_user_id' => $user->id
        ]);

        return redirect()->route('hr.index')
            ->with('success', 'HR updated successfully');
    }

    /**
     * HR logs (ADMIN ONLY)
     */
    public function logs(Request $request)
    {
        // dd($request->user_id);
        $user = User::find($request->user_id);
        $logs = UserLog::where('user_id', $user->id)->get();
        // $logs = debug_logs('HR');

        // dd($logs);

        return view('hr.logs', compact('logs'));
    }
    public function destroy(User $user)
    {

        // dd($user);
        $user->update(['is_deleted' => 1]);
        // dd($user);

        // Log deletion in user_logs
        UserLog::create([
            'user_id'      => $user->id,
            'performed_by' => auth()->id(),
            'module'       => 'HR',
            'action'       => 'delete',
            'model'        => 'User',
            'record_id'    => $user->id,
            'old_values'   => $user->getOriginal(),
        ]);

        return redirect()->route('hr.index')
            ->with('success', 'HR deleted successfully');
    }
}
