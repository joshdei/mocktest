<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form
     */
    public function show()
    {
        $user = Auth::user();
        $info = $user->info;
        return view('profile.edit', compact('user', 'info'));
    }

    /**
     * Update the user's profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'telephone'  => ['required', 'string', Rule::unique('users')->ignore($user->id)],
            'mat_number' => ['nullable', 'string', 'max:255'],
            'username'   => ['nullable', 'string', 'max:255'],
            'zone'       => ['nullable', 'string', 'max:255'],
            'study_centre' => ['nullable', 'string', 'max:255'],
            'faculty'    => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'programme'  => ['nullable', 'string', 'max:255'],
            'level'      => ['nullable', 'string', 'max:255'],
            'semester'   => ['nullable', 'string', 'max:255'],
            'password'   => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Update user
        $user->update([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'telephone'  => $data['telephone'],
        ]);

        // Update or create user information
        $user->info()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'mat_number'   => $data['mat_number'] ?? null,
                'username'     => $data['username'] ?? null,
                'zone'         => $data['zone'] ?? null,
                'study_centre' => $data['study_centre'] ?? null,
                'faculty'      => $data['faculty'] ?? null,
                'department'   => $data['department'] ?? null,
                'programme'    => $data['programme'] ?? null,
                'level'        => $data['level'] ?? null,
                'semester'     => $data['semester'] ?? null,
            ]
        );

        // Update password if provided
        if (!empty($data['password'])) {
            $user->update(['password' => Hash::make($data['password'])]);
        }

        return back()->with('status', 'Profile updated successfully!');
    }
}

