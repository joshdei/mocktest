<?php

namespace App\Console\Commands;

use App\Mail\WeeklyRankBoostMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendWeeklyRankBoostMails extends Command
{
    protected $signature = 'rank:send-weekly-boost';

    protected $description = 'Send weekly rank boost email to verified users.';

    public function handle(): int
    {
        $users = User::query()
            ->get();

        $verifiedUsers = $users->filter(fn (User $u) => method_exists($u, 'hasVerifiedEmail') && $u->hasVerifiedEmail());

        if ($verifiedUsers->isEmpty()) {
            $this->info('No verified users found.');
            return self::SUCCESS;
        }

        // NOTE: we don’t have a dedicated leaderboard model here.
        // We’re sending a “rank update” based on existing performance tests scoring logic.
        // If later you create a proper rank table, switch this part.

        foreach ($verifiedUsers as $user) {
            // Placeholder rank/points; your existing leaderboard uses Test::score.
            // Update later if you add a real rank calculation per week.
            $rank = 0;
            $points = 0;

            Mail::to($user->email)->send(new WeeklyRankBoostMail($user, $rank, $points));
        }

        Log::info('WeeklyRankBoostMail sent (placeholder rank/points).');
        $this->info('Weekly rank boost emails queued/sent.');

        return self::SUCCESS;
    }
}

