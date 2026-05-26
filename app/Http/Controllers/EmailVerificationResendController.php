<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmailLinkMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class EmailVerificationResendController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();
        if (! $user instanceof User) {
            abort(403);
        }

        // If already verified, no need to resend.
        if (! empty($user->email_verified_at)) {
            return back()->with('status', 'Your email is already verified.');
        }

        // Create a signed, expiring verification URL
        // Uses email_verified_at update via our custom verify route.
        $expiresAt = now()->addMinutes(60);
        $payload = [
            'id' => $user->id,
            'email' => $user->email,
            'sha' => hash('sha256', $user->email),
        ];

        // We use signed routes so we don't need a DB token.
        $verificationUrl = route('email.verify.custom', [
            'id' => $payload['id'],
            'hash' => $payload['sha'],
            // Laravel will sign/expire via URL::temporarySignedRoute
        ]);

        // Laravel's signed route helper
        $verificationUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'email.verify.custom',
            $expiresAt,
            ['id' => $payload['id'], 'hash' => $payload['sha']]
        );

        Mail::to($user->email)->send(new VerifyEmailLinkMail($user->first_name ?? $user->name, $verificationUrl));

        Log::info('Resent email verification link', ['user_id' => $user->id]);

        return back()->with('status', 'Verification link sent to your email.');
    }
}

