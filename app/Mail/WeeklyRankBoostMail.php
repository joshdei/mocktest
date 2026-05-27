<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeeklyRankBoostMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public int $rank;
    public $points;

    public function __construct(User $user, int $rank, $points)
    {
        $this->user = $user;
        $this->rank = $rank;
        $this->points = $points;
    }

    public function build(): self
    {
        return $this->subject('Your weekly rank update')
            ->view('emails.weekly_rank_boost');
    }
}

