<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserLoginLog;
use Carbon\Carbon;

class StreakService
{
    /**
     * Get current streak for a user
     */
    public function getCurrentStreak(User $user): int
    {
        $today = Carbon::now()->toDateString();
        $yesterday = Carbon::now()->subDay()->toDateString();

        // Check if user logged in today or yesterday
        $lastLogin = UserLoginLog::where('user_id', $user->id)
            ->orderBy('login_date', 'desc')
            ->first();

        if (! $lastLogin || ($lastLogin->login_date->toDateString() !== $today && $lastLogin->login_date->toDateString() !== $yesterday)) {
            return 0;
        }

        // Count consecutive days
        $streak = 0;
        $currentDate = Carbon::now()->toDateString();

        while (true) {
            $exists = UserLoginLog::where('user_id', $user->id)
                ->whereDate('login_date', $currentDate)
                ->exists();

            if (! $exists) {
                break;
            }

            $streak++;
            $currentDate = Carbon::createFromFormat('Y-m-d', $currentDate)->subDay()->toDateString();
        }

        return $streak;
    }

    /**
     * Get streak data including current streak and last 7 days
     */
    public function getStreakData(User $user): array
    {
        $currentStreak = $this->getCurrentStreak($user);

        // Get login dates for last 7 days
        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $exists = UserLoginLog::where('user_id', $user->id)
                ->whereDate('login_date', $date)
                ->exists();
            $last7Days[] = $exists;
        }

        return [
            'current_streak' => $currentStreak,
            'last_7_days' => $last7Days,
        ];
    }

    /**
     * Record a login for today
     */
    public function recordLogin(User $user): void
    {
        $today = Carbon::now()->toDateString();

        UserLoginLog::updateOrCreate(
            ['user_id' => $user->id, 'login_date' => $today],
            ['login_date' => $today]
        );
    }
}
