<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class VerifyEmailLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $userName,
        public readonly string $verificationUrl
    ) {
    }

    public function build(): static
    {
        return $this->subject('Verify Your Email')
            ->view('emails.verify-email')
            ->with([
                'userName' => $this->userName,
                'verificationUrl' => $this->verificationUrl,
            ]);
    }
}

