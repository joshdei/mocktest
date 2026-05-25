<?php

namespace App\Console\Commands;

use App\Mail\QotdReminderMail;
use App\Models\Question;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendQotdReminder extends Command
{
    protected $signature = 'qotd:remind {--dry-run : Do not send emails}';
    protected $description = 'Send Question of the Day reminder email to verified users.';

    public function handle(): int
    {
        $question = Question::query()
            ->with('course')
            ->whereNotNull('question')
            ->inRandomOrder()
            ->first();

        if (! $question) {
            $this->warn('No QOTD question found; skipping reminder.');
            return self::SUCCESS;
        }

        $verifiedUsers = User::query()
            ->whereNotNull('email_verified_at')
            ->pluck('email')
            ->filter()
            ->unique()
            ->values();

        if ($verifiedUsers->isEmpty()) {
            $this->warn('No verified users found; skipping reminder.');
            return self::SUCCESS;
        }

        $dryRun = (bool) $this->option('dry-run');

        $subjectToken = Str::upper($question->course_id . ':' . $question->id);

        foreach ($verifiedUsers as $email) {
            if ($dryRun) {
                $this->line("[dry-run] Would send reminder to {$email} (token: {$subjectToken}).");
                continue;
            }

            Mail::to($email)->send(new QotdReminderMail($question));
        }

        $this->info($dryRun ? 'Dry run completed.' : 'QOTD reminders sent.');

        return self::SUCCESS;
    }
}

