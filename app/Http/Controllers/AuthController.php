<?php

namespace App\Http\Controllers;

use App\Models\MockPrice;
use App\Models\User;
use App\Models\UserInformation;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $this->assignFreePlanIfMissing(Auth::user());

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }

    private function assignFreePlanIfMissing(User $user): void
    {
        $hasActiveSubscription = UserSubscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>=', now());
            })
            ->exists();

        if ($hasActiveSubscription) {
            return;
        }

        $freePlan = MockPrice::where('status', 'active')
            ->where(function ($query) {
                $query->where('price', '<=', 0)
                    ->orWhereRaw('LOWER(name) = ?', ['free']);
            })
            ->orderBy('order')
            ->first();

        if (! $freePlan) {
            return;
        }

        UserSubscription::create([
            'user_id' => $user->id,
            'plan_id' => $freePlan->id,
            'start_date' => now(),
            'expiry_date' => null,
            'status' => 'active',
        ]);
    }

    /**
     * Give ₦5 daily cashback if user is on the highest active plan.
     */

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'telephone' => ['required', 'string', 'unique:users,telephone'],
            // 'department' => ['required', 'string', 'max:255'],
            // 'level'      => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Create user
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'password' => Hash::make($data['password']),
        ]);

        // // Create user information
        // UserInformation::create([
        //     'user_id'    => $user->id,
        //     'department' => $data['department'],
        //     'level'      => $data['level'],
        // ]);

        // Log in
        Auth::login($user);

        return redirect()->route('dashboard');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Handle forgot password
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }
}
