<?php

namespace App\Console\Commands;

use App\Mail\DailyQuestionNotificationMail;
use App\Models\WeeklyQuestionSchedule;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class NotifyTodayQuestion extends Command
{
    protected $signature = 'questions:notify-today';
    protected $description = 'Send question notification email to verified users if today is the scheduled day.';

    public function handle(): int
    {
        $now = Carbon::now();
        $weekNumber = $now->isoWeek();
        $year = $now->year;
        $today = Carbon::today()->toDateString();

        $schedule = WeeklyQuestionSchedule::query()
            ->where('week_number', $weekNumber)
            ->where('year', $year)
            ->with('question')
            ->first();

        if (! $schedule || $schedule->scheduled_date->toDateString() !== $today) {
            return self::SUCCESS;
        }

        User::query()
            ->whereNotNull('email_verified_at')
            ->chunk(100, function ($users) use ($schedule) {
                foreach ($users as $user) {
                    Mail::to($user)->queue(new DailyQuestionNotificationMail(
                        $user->first_name,
                        $schedule
                    ));
                }
            });

        return self::SUCCESS;
    }
}

