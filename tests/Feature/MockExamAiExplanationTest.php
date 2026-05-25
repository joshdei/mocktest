<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Course;
use App\Models\Question;
use App\Models\UserSubscription;
use App\Models\MockPrice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MockExamAiExplanationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Course $course;
    private Question $question;
    private MockPrice $planWithAi;
    private MockPrice $planWithoutAi;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->course = Course::factory()->create();
        
        $this->question = Question::create([
            'course_id' => $this->course->id,
            'question' => 'What is 2 + 2?',
            'option_a' => '3',
            'option_b' => '4',
            'option_c' => '5',
            'option_d' => '6',
            'correct_answer' => 'b',
            'question_type' => 'mcq',
        ]);

        // Create plans with and without AI
        $this->planWithoutAi = MockPrice::create([
            'name' => 'Basic Plan',
            'price' => 5000,
            'currency' => 'NGN',
            'duration' => 30,
            'status' => 'active',
            'aistatus' => 0,
        ]);

        $this->planWithAi = MockPrice::create([
            'name' => 'Premium Plan',
            'price' => 10000,
            'currency' => 'NGN',
            'duration' => 30,
            'status' => 'active',
            'aistatus' => 1,
        ]);
    }

    public function test_user_without_ai_access_cannot_get_explanation(): void
    {
        // Create subscription without AI access
        UserSubscription::create([
            'user_id' => $this->user->id,
            'plan_id' => $this->planWithoutAi->id,
            'status' => 'active',
            'start_date' => now(),
            'expiry_date' => now()->addDays(30),
        ]);

        $this->actingAs($this->user);
        
        $response = $this->postJson(route('mock.ai-explain'), [
            'question_id' => $this->question->id,
            'user_answer' => 'a',
            'correct_answer' => 'b',
        ]);

        $response->assertStatus(403);
        $response->assertJsonPath('error', 'AI access not available');
    }

    public function test_user_with_ai_access_can_get_explanation(): void
    {
        // Create subscription with AI access
        UserSubscription::create([
            'user_id' => $this->user->id,
            'plan_id' => $this->planWithAi->id,
            'status' => 'active',
            'start_date' => now(),
            'expiry_date' => now()->addDays(30),
        ]);

        $this->actingAs($this->user);
        
        $response = $this->postJson(route('mock.ai-explain'), [
            'question_id' => $this->question->id,
            'user_answer' => 'a',
            'correct_answer' => 'b',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['explanation']);
        $response->assertJsonPath('explanation', 'This is an AI-generated explanation. Please configure the AI provider in the generateAiExplanation method.');
    }

    public function test_review_page_shows_ai_button_with_access(): void
    {
        // Create subscription with AI access
        UserSubscription::create([
            'user_id' => $this->user->id,
            'plan_id' => $this->planWithAi->id,
            'status' => 'active',
            'start_date' => now(),
            'expiry_date' => now()->addDays(30),
        ]);

        // Set exam results in session
        $results = [
            [
                'question' => $this->question,
                'question_id' => $this->question->id,
                'user_answer' => 'a',
                'correct_answer' => 'b',
                'is_correct' => false,
            ]
        ];

        $this->actingAs($this->user)
            ->withSession([
                'exam_results' => $results,
                'exam_course' => $this->course,
                'exam_score' => 0,
                'exam_correct' => 0,
                'exam_total' => 1,
                'exam_passed' => false,
            ])
            ->get(route('mock.review'))
            ->assertStatus(200)
            ->assertViewHas('hasAiAccess', true);
    }

    public function test_review_page_hides_ai_button_without_access(): void
    {
        // Create subscription without AI access
        UserSubscription::create([
            'user_id' => $this->user->id,
            'plan_id' => $this->planWithoutAi->id,
            'status' => 'active',
            'start_date' => now(),
            'expiry_date' => now()->addDays(30),
        ]);

        // Set exam results in session
        $results = [
            [
                'question' => $this->question,
                'question_id' => $this->question->id,
                'user_answer' => 'a',
                'correct_answer' => 'b',
                'is_correct' => false,
            ]
        ];

        $this->actingAs($this->user)
            ->withSession([
                'exam_results' => $results,
                'exam_course' => $this->course,
                'exam_score' => 0,
                'exam_correct' => 0,
                'exam_total' => 1,
                'exam_passed' => false,
            ])
            ->get(route('mock.review'))
            ->assertStatus(200)
            ->assertViewHas('hasAiAccess', false);
    }

    public function test_expired_subscription_cannot_access_ai(): void
    {
        // Create expired subscription
        UserSubscription::create([
            'user_id' => $this->user->id,
            'plan_id' => $this->planWithAi->id,
            'status' => 'active',
            'start_date' => now()->subDays(60),
            'expiry_date' => now()->subDays(1), // Expired yesterday
        ]);

        $this->actingAs($this->user);
        
        $response = $this->postJson(route('mock.ai-explain'), [
            'question_id' => $this->question->id,
            'user_answer' => 'a',
            'correct_answer' => 'b',
        ]);

        $response->assertStatus(403);
    }
}
