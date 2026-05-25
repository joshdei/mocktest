<?php

namespace App\Console\Commands;

use App\Models\StudyChallenge;
use Illuminate\Console\Command;

class ExpireChallenges extends Command
{
    protected $signature = 'challenges:expire';
    protected $description = 'Expire study challenges that are past their expiry time.';

    public function handle(): int
    {
        $expired = StudyChallenge::query()
            ->whereIn('status', ['pending', 'challenger_played'])
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->update(['status' => 'expired']);

        $this->info("Expired {$expired} study challenge(s).");



        return self::SUCCESS;
    }
}


