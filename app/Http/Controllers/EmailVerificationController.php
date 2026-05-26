<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmailMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class EmailVerificationController extends Controller
{
    /**
     * Generate a properly signed verification URL.
     * Produces: /email/verify/{id}/{hash}?expires=...&signature=...
     */
    private function generateVerificationUrl(User $user): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id'   => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );
    }

    /**
     * Send verification email using our custom Mailable.
     */
    public function send(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        if ($user->hasVerifiedEmail()) {
            return back()->with('status', 'Your email is already verified.');
        }

        try {
            $url = $this->generateVerificationUrl($user);

            Mail::to($user->email)->send(
                new VerifyEmailMail($url, $user->name)
            );
        } catch (\Throwable $e) {
            Log::error('Email verification send failed', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);

            return back()->with('error', 'Unable to send verification email.');
        }

        return back()->with('status', 'Verification email sent.');
    }

    /**
     * Show the "check your email" page.
     */
    public function notice()
    {
        return auth()->user()->hasVerifiedEmail()
            ? redirect()->route('dashboard')
            : view('auth.verify-email');
    }

    /**
     * Handle the signed verification link.
     * Route: /email/verify/{id}/{hash}
     */
    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        abort_if(
            ! hash_equals((string) $hash, sha1($user->getEmailForVerification())),
            403,
            'Invalid verification hash.'
        );

        abort_if(
            ! $request->hasValidSignature(),
            403,
            'Verification link has expired or is invalid.'
        );

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect()->route('dashboard')->with('verified', true);
    }
}