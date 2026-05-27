<?php

namespace App\Services;

use App\Models\Test;
use App\Models\TestAnswer;
use App\Models\User;
use Carbon\Carbon;

class PerformanceService
{
    /**
     * Get KPI data for the user
     */
    public function getKPIData(User $user, string $period = 'week'): array
    {
        $query = Test::where('user_id', $user->id);
        $previousQuery = Test::where('user_id', $user->id);

        [$startDate, $prevStartDate] = $this->getPeriodDates($period);

        $currentTests = $query->where('created_at', '>=', $startDate)->get();
        $previousTests = $previousQuery->where('created_at', '>=', $prevStartDate)
            ->where('created_at', '<', $startDate)
            ->get();

        $currentAccuracy = $this->calculateAccuracy($currentTests);
        $previousAccuracy = $this->calculateAccuracy($previousTests);

        $currentQuestions = $currentTests->sum('total_questions');
        $previousQuestions = $previousTests->sum('total_questions');

        $currentHours = $this->calculateHoursStudied($currentTests);
        $previousHours = $this->calculateHoursStudied($previousTests);

        $currentTestsCount = $currentTests->count();
        $previousTestsCount = $previousTests->count();

        return [
            'accuracy' => [
                'value' => round($currentAccuracy, 1),
                'delta' => round($currentAccuracy - $previousAccuracy, 1),
                'trend' => $currentAccuracy >= $previousAccuracy ? 'up' : 'dn',
            ],
            'questions' => [
                'value' => $currentQuestions,
                'delta' => $currentQuestions - $previousQuestions,
                'trend' => $currentQuestions >= $previousQuestions ? 'up' : 'dn',
            ],
            'hours' => [
                'value' => round($currentHours, 1),
                'delta' => round($currentHours - $previousHours, 1),
                'trend' => $currentHours >= $previousHours ? 'up' : 'dn',
            ],
            'tests' => [
                'value' => $currentTestsCount,
                'delta' => $currentTestsCount - $previousTestsCount,
                'trend' => $currentTestsCount >= $previousTestsCount ? 'up' : 'dn',
            ],
        ];
    }

    /**
     * Get topic performance data
     */
    public function getTopicScores(User $user, string $period = 'week'): array
    {
        [$startDate] = $this->getPeriodDates($period);

        $tests = Test::where('user_id', $user->id)
            ->where('created_at', '>=', $startDate)
            ->with('course')
            ->get();

        $topicData = [];

        foreach ($tests as $test) {
            $courseName = $test->course?->code ?? 'General';

            if (! isset($topicData[$courseName])) {
                $topicData[$courseName] = [
                    'correct' => 0,
                    'total' => 0,
                ];
            }

            $correct = TestAnswer::where('test_id', $test->id)
                ->where('is_correct', true)
                ->count();

            $topicData[$courseName]['correct'] += $correct;
            $topicData[$courseName]['total'] += $test->total_questions;
        }

        $result = [];
        foreach ($topicData as $topic => $data) {
            $pct = $data['total'] > 0 ? round(($data['correct'] / $data['total']) * 100, 0) : 0;
            $status = $pct >= 80 ? 'strong' : ($pct >= 60 ? 'avg' : 'focus');

            $result[] = [
                'name' => $topic,
                'pct' => $pct,
                'status' => $status,
            ];
        }

        usort($result, fn ($a, $b) => $b['pct'] <=> $a['pct']);

        return array_slice($result, 0, 5);
    }

    /**
     * Get leaderboard data
     */
    public function getLeaderboard(User $user, string $period = 'week'): array
    {
        [$startDate] = $this->getPeriodDates($period);

        $all = User::query()
            ->with('tests')
            ->get()
            ->map(function ($u) use ($startDate, $user) {
                $tests = $u->tests()
                    ->where('created_at', '>=', $startDate)
                    ->get();

                $score = $tests->sum('score');

                return [
                    'id' => $u->id,
                    'name' => trim(($u->first_name ?? '').' '.($u->last_name ?? '')),
                    'initials' => strtoupper(substr($u->first_name ?? '', 0, 1).substr($u->last_name ?? '', 0, 1)),
                    'score' => $score,
                    'you' => $u->id === $user->id,
                ];
            })
            // Keep anyone with a positive score OR the current user (so they always see their position)
            ->filter(fn ($u) => ($u['score'] ?? 0) > 0 || ($u['id'] ?? null) === $user->id)
            ->sortByDesc('score')
            ->values();

        $top5 = $all->take(5);
        $youInTop = $top5->contains('id', $user->id);

        if (! $youInTop) {
            $you = $all->firstWhere('id', $user->id);
            if ($you) {
                $top5 = $all
                    ->filter(fn ($u) => ($u['score'] ?? 0) > 0 && ($u['id'] ?? null) !== $user->id)
                    ->take(4)
                    ->values();

                $top5 = $top5
                    ->push($you)
                    ->sortByDesc('score')
                    ->values()
                    ->take(5);
            }
        }

        return $top5->toArray();
    }

    /**
     * Get weekly activity heatmap
     */
    public function getWeeklyHeatmap(User $user): array
    {
        $heatData = [];

        // Get last 4 weeks of data
        for ($week = 3; $week >= 0; $week--) {
            for ($day = 0; $day < 7; $day++) {
                $date = Carbon::now()->subWeeks($week)->startOfWeek()->addDays($day);

                $count = TestAnswer::whereHas('test', function ($q) use ($user, $date) {
                    $q->where('user_id', $user->id)
                        ->whereDate('created_at', $date->toDateString());
                })->count();

                $heatData[] = $count;
            }
        }

        return $heatData;
    }

    /**
     * Calculate accuracy percentage
     */
    private function calculateAccuracy($tests): float
    {
        if ($tests->isEmpty()) {
            return 0;
        }

        $totalCorrect = 0;
        $totalAnswers = 0;

        foreach ($tests as $test) {
            $correct = TestAnswer::where('test_id', $test->id)
                ->where('is_correct', true)
                ->count();

            $totalCorrect += $correct;
            $totalAnswers += $test->total_questions;
        }

        return $totalAnswers > 0 ? ($totalCorrect / $totalAnswers) * 100 : 0;
    }

    /**
     * Calculate hours studied
     */
    private function calculateHoursStudied($tests): float
    {
        // Estimate: assume average test takes 20 minutes
        $testCount = $tests->count();

        return ($testCount * 20) / 60;
    }

    /**
     * Get period start dates
     */
    private function getPeriodDates(string $period): array
    {
        return match ($period) {
            'month' => [
                Carbon::now()->startOfMonth(),
                Carbon::now()->subMonth()->startOfMonth(),
            ],
            'all' => [
                Carbon::now()->subYears(10),
                Carbon::now()->subYears(20),
            ],
            'week' => [
                Carbon::now()->startOfWeek(),
                Carbon::now()->subWeek()->startOfWeek(),
            ],
        };
    }
}
