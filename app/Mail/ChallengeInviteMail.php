<?php

namespace App\Mail;

use App\Models\StudyChallenge;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChallengeInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public StudyChallenge $challenge;
    public string $challengerFirstName;
    public int $challengerScore;

    public function __construct(StudyChallenge $challenge, string $challengerFirstName, int $challengerScore)
    {
        $this->challenge = $challenge;
        $this->challengerFirstName = $challengerFirstName;
        $this->challengerScore = $challengerScore;
    }

    public function build(): static
    {
        $opponentName = $this->challenge->opponent?->first_name ?? '';
        $challengerName = $this->challengerFirstName;

        return $this->subject("⚡ {$challengerName} challenged you on PsalmEdu!")
            ->view('emails.challenges.invite');
    }
}
