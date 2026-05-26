<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EmailVerificationController extends Controller
{
    /**
     * Resend verification notification for the authenticated user.
     */
    public function send(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        // If your User model uses MustVerifyEmail, this will work.
        // If not, you can extend this to your own verification flow.
        if (method_exists($user, 'sendEmailVerificationNotification')) {
            try {
                $user->sendEmailVerificationNotification();
            } catch (\Throwable $e) {
                Log::error('Email verification resend failed', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);

                return back()->with('error', 'Unable to resend verification email.');
            }

            return back()->with('status', 'Verification email sent.');
        }

        return back()->with('error', 'Email verification is not configured for this account.');
    }


    public function notice()
{
    return auth()->user()->hasVerifiedEmail()
        ? redirect()->route('dashboard')
        : view('auth.verify-email');
}

public function verify(Request $request, $id, $hash)
{
    $user = \App\Models\User::findOrFail($id);

    abort_if(! hash_equals((string) $hash, sha1($user->getEmailForVerification())), 403);
    abort_if(! $request->hasValidSignature(), 403);

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    return redirect()->route('dashboard')->with('verified', true);
}
}

