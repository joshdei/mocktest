<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MockExamSidebarToggleTest extends TestCase
{
    use RefreshDatabase;

    public function test_exam_sidebar_toggle_uses_page_specific_handler(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $question = Question::create([
            'course_id' => $course->id,
            'question' => 'What is 2 + 2?',
            'option_a' => '3',
            'option_b' => '4',
            'option_c' => '5',
            'option_d' => '6',
            'answer' => 'B',
            'question_type' => 'mcq',
        ]);

        $response = $this
            ->actingAs($user)
            ->view('mock.exam', [
                'course' => $course,
                'questions' => collect([$question]),
                'duration' => 30,
            ]);

        $response->assertSee('id="sidebar-toggle"', false);
        $response->assertSee("toggleBtn.addEventListener('click', toggleExamSidebar)", false);
        $response->assertSee('function toggleExamSidebar()', false);
        $response->assertDontSee("toggleBtn.addEventListener('click', toggleSidebar)", false);
    }
}
