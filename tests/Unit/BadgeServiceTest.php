<?php

namespace Tests\Unit;

use App\Models\Badge;
use App\Models\Course;
use App\Models\Test;
use App\Models\User;
use App\Services\BadgeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BadgeServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_stores_and_awards_badges_for_completed_tasks(): void
    {
        $user = $this->createUser();
        $course = Course::create([
            'course_name' => 'Computer Information Technology',
            'course_code' => 'CIT101',
        ]);

        Test::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'score' => 10,
            'total_questions' => 10,
        ]);

        $badgeData = app(BadgeService::class)->syncForUser(
            $user,
            ['current_streak' => 7],
            [['id' => $user->id, 'score' => 10]]
        );

        $this->assertDatabaseHas('badges', ['name' => 'Streak Starter']);
        $this->assertDatabaseHas('badges', ['name' => 'Perfect Score']);
        $this->assertDatabaseHas('user_badges', [
            'user_id' => $user->id,
            'badge_id' => Badge::where('name', 'Perfect Score')->value('id'),
        ]);
        $this->assertDatabaseHas('user_badges', [
            'user_id' => $user->id,
            'badge_id' => Badge::where('name', 'Consistent Scholar')->value('id'),
        ]);
        $this->assertSame(6, $badgeData['earned_count']);
        $this->assertSame(2, $badgeData['locked_count']);
    }

    public function test_next_badge_tracks_nearest_locked_progress(): void
    {
        $user = $this->createUser();
        $course = Course::create([
            'course_name' => 'Computer Architecture',
            'course_code' => 'CSC101',
        ]);

        foreach (range(1, 3) as $index) {
            Test::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'score' => 6,
                'total_questions' => 10,
            ]);
        }

        $badgeData = app(BadgeService::class)->syncForUser($user);

        $this->assertSame('Scholar', $badgeData['next']['name']);
        $this->assertSame(3, $badgeData['next']['progress']['current']);
        $this->assertSame(5, $badgeData['next']['progress']['target']);
    }

    private function createUser(): User
    {
        return User::create([
            'first_name' => 'Test',
            'last_name' => 'Student',
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password',
        ]);
    }
}
