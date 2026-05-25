<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\Test;
use App\Models\User;
use Illuminate\Support\Collection;

class BadgeService
{
    /**
     * @return array<int, array{
     *     name: string,
     *     icon: string,
     *     color: string,
     *     description: string,
     *     required_tests: int,
     *     required_score: int,
     *     order: int
     * }>
     */
    public function definitions(): array
    {
        return [
            [
                'name' => 'Streak Starter',
                'icon' => 'fire',
                'color' => '#16A34A',
                'description' => 'Log in and start a daily streak.',
                'required_tests' => 0,
                'required_score' => 0,
                'order' => 1,
            ],
            [
                'name' => 'First Mock',
                'icon' => 'file-text',
                'color' => '#2563EB',
                'description' => 'Complete your first mock test.',
                'required_tests' => 1,
                'required_score' => 0,
                'order' => 2,
            ],
            [
                'name' => 'Scholar',
                'icon' => 'award',
                'color' => '#D97706',
                'description' => 'Complete 5 mock tests.',
                'required_tests' => 5,
                'required_score' => 0,
                'order' => 3,
            ],
            [
                'name' => 'Top 10',
                'icon' => 'star',
                'color' => '#CA8A04',
                'description' => 'Reach the top 10 on the weekly leaderboard.',
                'required_tests' => 0,
                'required_score' => 0,
                'order' => 4,
            ],
            [
                'name' => 'CIT Expert',
                'icon' => 'brain',
                'color' => '#7C3AED',
                'description' => 'Score at least 70% on a CIT course mock.',
                'required_tests' => 1,
                'required_score' => 70,
                'order' => 5,
            ],
            [
                'name' => 'Consistent Scholar',
                'icon' => 'flame',
                'color' => '#DC2626',
                'description' => 'Maintain a 7-day login streak.',
                'required_tests' => 0,
                'required_score' => 0,
                'order' => 6,
            ],
            [
                'name' => 'Perfect Score',
                'icon' => 'target',
                'color' => '#059669',
                'description' => 'Score 100% on any mock test.',
                'required_tests' => 1,
                'required_score' => 100,
                'order' => 7,
            ],
            [
                'name' => 'Professor',
                'icon' => 'crown',
                'color' => '#111827',
                'description' => 'Complete 20 mock tests.',
                'required_tests' => 20,
                'required_score' => 0,
                'order' => 8,
            ],
        ];
    }

    public function syncForUser(User $user, array $streakData = [], array $leaderboard = []): array
    {
        $badges = $this->ensureBadges();
        $metrics = $this->metricsFor($user, $streakData, $leaderboard);

        foreach ($badges as $badge) {
            if ($this->isEarned($badge, $metrics)) {
                $user->badges()->syncWithoutDetaching([
                    $badge->id => [
                        'earned_at' => now(),
                        'metadata' => json_encode($metrics),
                    ],
                ]);
            }
        }

        $earnedBadgeIds = $user->badges()->pluck('badges.id')->all();
        $badgeItems = $badges->map(function (Badge $badge) use ($earnedBadgeIds, $metrics): array {
            $earned = in_array($badge->id, $earnedBadgeIds, true);

            return [
                'id' => $badge->id,
                'name' => $badge->name,
                'icon' => $badge->icon,
                'description' => $badge->description,
                'earned' => $earned,
                'progress' => $this->progressFor($badge, $metrics),
            ];
        });

        return [
            'items' => $badgeItems,
            'earned_count' => $badgeItems->where('earned', true)->count(),
            'locked_count' => $badgeItems->where('earned', false)->count(),
            'next' => $this->nextBadge($badgeItems),
        ];
    }

    /**
     * @return Collection<int, Badge>
     */
    private function ensureBadges(): Collection
    {
        foreach ($this->definitions() as $definition) {
            Badge::updateOrCreate(
                ['name' => $definition['name']],
                [
                    ...$definition,
                    'type' => 'achievement',
                    'is_active' => true,
                ]
            );
        }

        return Badge::query()
            ->active()
            ->whereIn('name', collect($this->definitions())->pluck('name'))
            ->orderBy('order')
            ->get();
    }

    /**
     * @param  array<string, mixed>  $streakData
     * @param  array<int, array<string, mixed>>  $leaderboard
     * @return array<string, mixed>
     */
    private function metricsFor(User $user, array $streakData, array $leaderboard): array
    {
        $tests = Test::query()
            ->where('user_id', $user->id)
            ->where('total_questions', '>', 0)
            ->with('course')
            ->get();

        $topRank = collect($leaderboard)
            ->values()
            ->search(fn (array $leader) => (int) ($leader['id'] ?? 0) === $user->id);

        return [
            'current_streak' => (int) ($streakData['current_streak'] ?? 0),
            'tests_completed' => $tests->count(),
            'has_perfect_score' => $tests->contains(fn (Test $test) => $test->percentageScore() >= 100),
            'has_cit_expert_score' => $tests->contains(function (Test $test): bool {
                $courseCode = strtoupper((string) $test->course?->course_code);

                return str_starts_with($courseCode, 'CIT') && $test->percentageScore() >= 70;
            }),
            'leaderboard_rank' => $topRank === false ? null : $topRank + 1,
        ];
    }

    /**
     * @param  array<string, mixed>  $metrics
     */
    private function isEarned(Badge $badge, array $metrics): bool
    {
        return match ($badge->name) {
            'Streak Starter' => $metrics['current_streak'] >= 1,
            'First Mock' => $metrics['tests_completed'] >= 1,
            'Scholar' => $metrics['tests_completed'] >= 5,
            'Top 10' => $metrics['leaderboard_rank'] !== null && $metrics['leaderboard_rank'] <= 10,
            'CIT Expert' => $metrics['has_cit_expert_score'],
            'Consistent Scholar' => $metrics['current_streak'] >= 7,
            'Perfect Score' => $metrics['has_perfect_score'],
            'Professor' => $metrics['tests_completed'] >= 20,
            default => false,
        };
    }

    /**
     * @param  array<string, mixed>  $metrics
     * @return array{current: int, target: int, label: string, percent: int}
     */
    private function progressFor(Badge $badge, array $metrics): array
    {
        [$current, $target, $label] = match ($badge->name) {
            'Streak Starter' => [$metrics['current_streak'], 1, 'day streak'],
            'First Mock' => [$metrics['tests_completed'], 1, 'mock completed'],
            'Scholar' => [$metrics['tests_completed'], 5, 'mocks completed'],
            'Consistent Scholar' => [$metrics['current_streak'], 7, 'day streak'],
            'Professor' => [$metrics['tests_completed'], 20, 'mocks completed'],
            'Perfect Score' => [$metrics['has_perfect_score'] ? 1 : 0, 1, 'perfect score'],
            'CIT Expert' => [$metrics['has_cit_expert_score'] ? 1 : 0, 1, 'CIT score'],
            'Top 10' => [$metrics['leaderboard_rank'] !== null && $metrics['leaderboard_rank'] <= 10 ? 1 : 0, 1, 'top 10 rank'],
            default => [0, 1, 'progress'],
        };

        return [
            'current' => min((int) $current, $target),
            'target' => $target,
            'label' => $label,
            'percent' => min(100, (int) round((((int) $current) / max(1, $target)) * 100)),
        ];
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $badgeItems
     * @return array<string, mixed>|null
     */
    private function nextBadge(Collection $badgeItems): ?array
    {
        return $badgeItems
            ->where('earned', false)
            ->sortByDesc(fn (array $badge) => $badge['progress']['percent'])
            ->first();
    }
}
