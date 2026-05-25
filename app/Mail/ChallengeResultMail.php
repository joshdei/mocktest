<?php

namespace App\Mail;

use App\Models\StudyChallenge;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChallengeResultMail extends Mailable
{
    use Queueable, SerializesModels;

    public StudyChallenge $challenge;

    public function __construct(StudyChallenge $challenge)
    {
        $this->challenge = $challenge;
    }

    public function build(): static
    {
        $challenger = $this->challenge->challenger;
        $opponent = $this->challenge->opponent;

        $challengerScore = $this->challenge->challenger_score;
        $opponentScore = $this->challenge->opponent_score;

        $winnerId = $this->challenge->winner_id;

        $winnerLabel = 'Draw';
        if ($winnerId !== null) {
            $winnerLabel = $winnerId === $challenger?->id
                ? ($challenger?->first_name ?? 'Challenger')
                : ($opponent?->first_name ?? 'Opponent');
        }

        $subject = $winnerId === null
            ? '🤝 Study Challenge Result: It’s a Draw!'
            : "🏆 Study Challenge Result: {$winnerLabel} won!";

        return $this->subject($subject)
            ->view('emails.challenges.result')
            ->with([
                'challenger' => $challenger,
                'opponent' => $opponent,
                'challengerScore' => $challengerScore,
                'opponentScore' => $opponentScore,
                'winnerId' => $winnerId,
            ]);
    }
}
