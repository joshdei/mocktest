<?php

namespace App\Console\Commands;

use App\Models\Question;
use App\Models\WeeklyQuestionSchedule;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ScheduleWeeklyQuestions extends Command
{
    protected $signature = 'questions:schedule-weekly';
    protected $description = 'Schedule a random Question of the Day for the week.';

    public function handle(): int
    {
        $now = Carbon::now();
        $weekNumber = $now->isoWeek();
        $year = $now->year;

        $existing = WeeklyQuestionSchedule::query()
            ->where('week_number', $weekNumber)
            ->where('year', $year)
            ->first();

        if ($existing) {
            $this->info('Weekly schedule already exists. Skipping.');
            return self::SUCCESS;
        }

        $monday = $now->copy()->startOfWeek(Carbon::MONDAY);
        $offset = random_int(0, 6); // Monday=0 ... Sunday=6
        $scheduledDate = $monday->copy()->addDays($offset)->toDateString();

        $unusedMcq = Question::query()
            ->where('question_type', 'mcq')
            ->whereNotIn('id', function ($q) {
                $q->select('question_id')->from('weekly_question_schedule');
            })
            ->inRandomOrder()
            ->first();

        $question = $unusedMcq;

        if (! $question) {
            $question = Question::query()
                ->whereNotIn('id', function ($q) {
                    $q->select('question_id')->from('weekly_question_schedule');
                })
                ->inRandomOrder()
                ->first();
        }

        if (! $question) {
            $this->warn('No unused questions found to schedule.');
            return self::SUCCESS;
        }

        WeeklyQuestionSchedule::query()->create([
            'question_id' => $question->id,
            'week_number' => $weekNumber,
            'year' => $year,
            'scheduled_date' => $scheduledDate,
        ]);

        $this->info("Scheduled question {$question->id} for week {$weekNumber}, {$year} on {$scheduledDate}.");
        return self::SUCCESS;
    }
}

