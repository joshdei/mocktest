<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QotdControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_current_returns_question_payload(): void
    {
        $user = $this->createUser();
        $course = Course::create([
            'course_name' => 'Computer Architecture',
            'course_code' => 'CIT101',
        ]);
        $question = Question::create([
            'course_id' => $course->id,
            'question' => 'What does cache memory do?',
            'option_a' => 'Permanent operating system storage',
            'option_b' => 'High-speed buffer between CPU and main memory',
            'option_c' => 'External secondary storage',
            'option_d' => 'Virtual memory managed by the OS',
            'answer' => 'B',
            'question_type' => 'mcq',
        ]);

        $response = $this->actingAs($user)->getJson(route('qotd.current'));

        $response
            ->assertOk()
            ->assertJsonPath('question_id', $question->id)
            ->assertJsonPath('course_label', 'CIT101 - Computer Architecture')
            ->assertJsonPath('options.B', 'High-speed buffer between CPU and main memory');
    }

    public function test_submit_returns_result_payload(): void
    {
        $user = $this->createUser();
        $course = Course::create([
            'course_name' => 'Computer Architecture',
            'course_code' => 'CIT101',
        ]);
        $question = Question::create([
            'course_id' => $course->id,
            'question' => 'What does cache memory do?',
            'option_a' => 'Permanent operating system storage',
            'option_b' => 'High-speed buffer between CPU and main memory',
            'option_c' => 'External secondary storage',
            'option_d' => 'Virtual memory managed by the OS',
            'answer' => 'B',
            'question_type' => 'mcq',
        ]);

        $response = $this->actingAs($user)->postJson(route('qotd.submit'), [
            'question_id' => $question->id,
            'selected_option' => 'B',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('selected_option', 'B')
            ->assertJsonPath('correct_option', 'B')
            ->assertJsonPath('is_correct', true)
            ->assertJsonPath('points', 7);
    }

    public function test_current_returns_fill_question_type(): void
    {
        $user = $this->createUser();
        $course = Course::create([
            'course_name' => 'Computer Architecture',
            'course_code' => 'CIT101',
        ]);
        $question = Question::create([
            'course_id' => $course->id,
            'question' => 'CPU stands for ____.',
            'answer' => 'Central Processing Unit',
            'question_type' => 'fill',
        ]);

        $response = $this->actingAs($user)->getJson(route('qotd.current'));

        $response
            ->assertOk()
            ->assertJsonPath('question_id', $question->id)
            ->assertJsonPath('question_type', 'fill')
            ->assertJsonPath('options.A', null);
    }

    public function test_submit_accepts_fill_answer(): void
    {
        $user = $this->createUser();
        $course = Course::create([
            'course_name' => 'Computer Architecture',
            'course_code' => 'CIT101',
        ]);
        $question = Question::create([
            'course_id' => $course->id,
            'question' => 'CPU stands for ____.',
            'answer' => 'Central Processing Unit',
            'question_type' => 'fill',
        ]);

        $response = $this->actingAs($user)->postJson(route('qotd.submit'), [
            'question_id' => $question->id,
            'answer' => ' central processing unit ',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('selected_option', 'central processing unit')
            ->assertJsonPath('correct_answer', 'Central Processing Unit')
            ->assertJsonPath('is_correct', true)
            ->assertJsonPath('points', 0);
    }

    private function createUser(): User
    {
        $user = User::create([
            'first_name' => 'Test',
            'last_name' => 'Student',
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password',
        ]);

        $user->forceFill(['email_verified_at' => now()])->save();

        return $user;
    }
}
