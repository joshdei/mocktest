<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class EmailVerificationController extends Controller
{
    /**
     * Sends a new email verification link to the currently authenticated user.
     *
     * This exists because your app currently doesn’t register Laravel’s built-in
     * verification routes (route name: verification.notice).
     */
    public function resend(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        // If you later re-enable Laravel's verification routes, this method
        // may be replaced by the built-in ones.
        if (method_exists($user, 'hasVerifiedEmail') && $user->hasVerifiedEmail()) {
            return redirect()->back()->with('status', 'Your email is already verified.');
        }

        // If your User model uses MustVerifyEmail + Laravel notifications, this works.
        if (method_exists($user, 'sendEmailVerificationNotification')) {
            $user->sendEmailVerificationNotification();

            return redirect()->back()->with('status', 'Verification link sent. Please check your email.');
        }

        // Fallback: create a temporary verification URL if your app isn’t using
        // Laravel’s MustVerifyEmail scaffolding.
        // NOTE: This fallback will only work if you have a listener/controller
        // to handle whatever URL you generate.
        $verificationUrl = URL::to('/email/verify') . '?token=' . Str::random(40);

        // You would need to actually send an email yourself here.
        // For now, we just redirect with a message.
        return redirect()->back()->with('warning', 'Email verification is not fully configured.');
    }
}

