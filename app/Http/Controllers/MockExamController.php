<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\ExamAttempt;
use App\Models\MockPrice;
use App\Models\MockPrice2;
use App\Models\Question;
use App\Models\Test;
use App\Models\TestAnswer;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserSubscription;
use App\Services\BadgeService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;

class MockExamController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $query = Course::withCount('questions');

        if ($search) {
            $query->where('course_code', 'like', "%{$search}%")
                ->orWhere('course_name', 'like', "%{$search}%");
        }

        $query->having('questions_count', '>', 0);

        $courses = $query->orderBy('course_code')->paginate(12);

        return view('mock.index', compact('courses', 'search'));
    }

    public function setup($courseId)
    {
        $course = Course::findOrFail($courseId);

        $plans = MockPrice::where('status', 'active')
            ->orderBy('order', 'asc')
            ->get();

        $userSubscription = UserSubscription::where('user_id', auth()->id())
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>=', now());
            })
            ->whereHas('plan', function ($query) {
                $query->whereNotIn('name', ['Free', 'Basic', 'Default']);
            })
            ->with('plan')
            ->first();

        return view('mock.setup', compact('course', 'plans', 'userSubscription'));
    }

    public function charge(Request $request, $courseId, $planId)
    {
        $course = Course::findOrFail($courseId);
        $plan = MockPrice::findOrFail($planId);
        $user = Auth::user();

        $wallet = $user->wallet;
        $balance = $wallet->balance;

        if ($balance < $plan->price) {
            return redirect()->route('mock.setup', $course->id)
                ->with('error', 'Insufficient wallet balance. You need ₦'.number_format($plan->price).' but only have ₦'.number_format($balance));
        }

        $wallet->update([
            'balance' => $balance - $plan->price,
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'type' => 'debit',
            'amount' => $plan->price,
            'description' => "Exam attempt: {$course->course_name} - {$plan->name} Plan",
            'status' => 'completed',
            'reference' => 'EXAM-'.strtoupper(uniqid()),
        ]);

        // Store plan id in session so setup2 and start can access it without URL param
        session(['exam_plan_id' => $plan->id]);

        return redirect()->route('mock.setup2', [$course->id])
            ->with('success', '₦'.number_format($plan->price).' deducted from your wallet. Good luck!');
    }

    public function setup2(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        $questions = Question::query()->where('course_id', $course->id)->get();
        $duration = 60;

        $mode = $request->input('mode', 'paid');

        // ✅ Check GET param first, fallback to session (for basic plan redirect from charge())
        $planId = $request->input('plan_id') ?? session('exam_plan_id');

        // Free trial — find free plan
        if ($mode === 'free') {
            $plan = MockPrice::where('price', 0)->first();
            $planId = $plan?->id;
        } else {
            $plan = $planId ? MockPrice::find($planId) : null;
        }

        // ✅ Always keep session fresh
        session([
            'exam_plan_id' => $planId,
            'exam_mode' => $mode,
        ]);

        return view('mock.setup2', compact('course', 'questions', 'duration', 'plan', 'mode', 'planId'));
    }

    public function start(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        $duration = $request->input('duration', 60);

        // Get plan_id from POST first, fallback to session
        $planId = $request->input('plan_id') ?? session('exam_plan_id');

        if (! $planId) {
            return redirect()->route('mock.setup', $courseId)
                ->with('error', 'Session expired. Please select a plan again.');
        }

        // Store it in session so it persists
        session(['exam_plan_id' => $planId]);

        $plan = MockPrice::findOrFail($planId);

        $numberOfQuestions = MockPrice2::where('plan_id', $plan->id)->value('number_of_question');
        $isFreeplan = false;

        $userSubscription = UserSubscription::where('user_id', auth()->id())
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>=', now());
            })
            ->with(['plan'])
            ->first();

        if ($userSubscription && $userSubscription->plan) {
            $isFreeplan = in_array(strtolower($userSubscription->plan->name), ['free', 'default']);
        }

        $questionsQuery = Question::query()->where('course_id', $courseId);

        if (! $isFreeplan) {
            $questionsQuery->inRandomOrder();
        }

        $questions = $questionsQuery->take($numberOfQuestions)->get();

        if ($questions->isEmpty()) {
            return redirect()->route('mock.index')
                ->with('error', 'No questions available for this course.');
        }

        $attempt = ExamAttempt::create([
            'user_id' => auth()->id(),
            'course_id' => $courseId,
            'plan_id' => $plan->id,
            'plan_name' => $plan->name,
            'num_questions' => $questions->count(),
            'duration' => $duration,
            'started_at' => now(),
        ]);

        session([
            'exam_course_id' => $courseId,
            'exam_questions' => $questions->pluck('id')->toArray(),
            'exam_duration' => $duration,
            'exam_start_time' => now()->timestamp,
            'exam_answers' => [],
            'exam_attempt_id' => $attempt->id,
        ]);

        return view('mock.exam', compact('course', 'questions', 'duration'));
    }

    public function submit(Request $request)
    {
        $courseId = session('exam_course_id');
        $questionIds = session('exam_questions', []);

        if (! $questionIds) {
            return redirect()->route('mock.index')->with('error', 'No exam in progress.');
        }

        $course = Course::findOrFail($courseId);

        $elapsedSeconds = (int) $request->input('time_used', 0);
        $minutes = floor($elapsedSeconds / 60);
        $seconds = $elapsedSeconds % 60;
        $timeUsed = sprintf('%d min %02d sec', $minutes, $seconds);

        $userAnswers = $request->input('answers', []);
        $questions = Question::whereIn('id', $questionIds)->get();
        $totalQuestions = $questions->count();
        $correctAnswers = 0;
        $results = [];

        foreach ($questions as $question) {
            $userAnswer = $userAnswers[$question->id] ?? null;
            $correctAnswer = $question->answer ?? $question->correct_answer ?? '';
            $isCorrect = false;

            if ($question->question_type === 'mcq') {
                $isCorrect = strtoupper($userAnswer) === strtoupper($correctAnswer);
            } else {
                $isCorrect = strtolower(trim($userAnswer ?? '')) === strtolower(trim($correctAnswer));
            }

            if ($isCorrect) {
                $correctAnswers++;
            }

            $results[] = [
                'question' => $question,
                'user_answer' => $userAnswer,
                'correct_answer' => $correctAnswer,
                'is_correct' => $isCorrect,
                'question_type' => $question->question_type,
            ];
        }

        $score = round(($correctAnswers / max(1, $totalQuestions)) * 100);
        $passed = $score >= 50;

        DB::transaction(function () use ($courseId, $correctAnswers, $results, $totalQuestions, $userAnswers) {
            $test = Test::create([
                'user_id' => auth()->id(),
                'course_id' => $courseId,
                'score' => $correctAnswers,
                'total_questions' => $totalQuestions,
            ]);

            foreach ($results as $result) {
                $question = $result['question'];

                TestAnswer::create([
                    'test_id' => $test->id,
                    'question_id' => $question->id,
                    'selected_option' => (string) ($userAnswers[$question->id] ?? ''),
                    'is_correct' => $result['is_correct'],
                ]);
            }

            if ($attemptId = session('exam_attempt_id')) {
                ExamAttempt::whereKey($attemptId)->update([
                    'finished_at' => now(),
                    'score' => round(($correctAnswers / max(1, $totalQuestions)) * 100),
                ]);
            }

            session(['exam_test_id' => $test->id]);
        });

        app(BadgeService::class)->syncForUser($request->user());

        session([
            'exam_results' => $results,
            'exam_course' => $course,
            'exam_score' => $score,
            'exam_correct' => $correctAnswers,
            'exam_total' => $totalQuestions,
            'exam_passed' => $passed,
            'exam_time_used' => $timeUsed,  // ✅ store it
        ]);

        session()->forget(['exam_course_id', 'exam_questions', 'exam_duration', 'exam_start_time', 'exam_answers', 'exam_attempt_id']);

        return redirect()->route('mock.result');
    }

    public function result(Request $request)
    {
        $results = session('exam_results');
        $course = session('exam_course');

        if (! $results || ! $course) {
            $testId = session('exam_test_id') ?? $request->query('test_id');
            $testQuery = Test::with(['course', 'answers.question'])
                ->where('user_id', auth()->id())
                ->where('total_questions', '>', 0);
            $test = $testId ? $testQuery->find($testId) : $testQuery->latest()->first();

            if (! $test) {
                return redirect()->route('mock.results')->with('error', 'No exam result found.');
            }

            [
                'course' => $course,
                'results' => $results,
                'score' => $score,
                'correctAnswers' => $correctAnswers,
                'totalQuestions' => $totalQuestions,
                'passed' => $passed,
            ] = $this->buildResultsFromTest($test);

            session(['exam_test_id' => $test->id]);

            $timeUsed = session('exam_time_used', 'â€”');
        } else {
            $score = session('exam_score');
            $correctAnswers = session('exam_correct');
            $totalQuestions = session('exam_total');
            $passed = session('exam_passed');
            $timeUsed = session('exam_time_used', 'â€”');  // âœ… read it
        }
        $wrongAnswers = $totalQuestions - $correctAnswers;
        $percentage = $score;

        $gradeMap = ['A' => 70, 'B' => 60, 'C' => 50, 'D' => 40, 'F' => 0];
        $grade = 'F';
        foreach ($gradeMap as $g => $minScore) {
            if ($percentage >= $minScore) {
                $grade = $g;
                break;
            }
        }

        $statusMessage = $passed
            ? "Great work! You've passed this exam. Keep it up!"
            : 'You need more preparation. Study the materials and retake.';
        $statusIcon = $passed ? '✓' : '❌';
        $statusText = $passed ? 'Pass' : 'Fail';

        foreach ($results as $index => $result) {
            $results[$index]['index'] = $index;
        }

        $perPage = 10;
        $currentPage = (int) request()->get('page', 1);
        $totalItems = count($results);
        $totalPages = max(1, ceil($totalItems / $perPage));
        $currentPage = max(1, min($currentPage, $totalPages));

        $offset = ($currentPage - 1) * $perPage;
        $paginatedResults = array_slice($results, $offset, $perPage);

        $paginationLinks = [];
        for ($i = 1; $i <= $totalPages; $i++) {
            $paginationLinks[] = [
                'label' => $i,
                'url' => request()->url().'?page='.$i,
                'active' => $i === $currentPage,
            ];
        }

        $prevPage = $currentPage > 1 ? request()->url().'?page='.($currentPage - 1) : null;
        $nextPage = $currentPage < $totalPages ? request()->url().'?page='.($currentPage + 1) : null;

        return view('mock.result', [
            'course' => $course,
            'results' => $results,
            'paginatedResults' => $paginatedResults,
            'score' => $score,
            'percentage' => $percentage,
            'correctAnswers' => $correctAnswers,
            'wrongAnswers' => $wrongAnswers,
            'totalQuestions' => $totalQuestions,
            'passed' => $passed,
            'grade' => $grade,
            'statusIcon' => $statusIcon,
            'statusText' => $statusText,
            'statusMessage' => $statusMessage,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'paginationLinks' => $paginationLinks,
            'prevPageUrl' => $prevPage,
            'nextPageUrl' => $nextPage,
            'timeUsed' => $timeUsed,  // ✅ now correct
        ]);
    }

    // Review page - shows all questions with answers
    public function review(Request $request)
    {
        // Check if we have stored results in session
        $results = session('exam_results');
        $course = session('exam_course');

        if (! $results || ! $course) {
            $testId = $request->query('test_id') ?? session('exam_test_id');
            $testQuery = Test::with(['course', 'answers.question'])
                ->where('user_id', auth()->id())
                ->where('total_questions', '>', 0);
            $test = $testId ? $testQuery->find($testId) : $testQuery->latest()->first();

            if (! $test) {
                return redirect()->route('mock.results')->with('error', 'No exam result found.');
            }

            [
                'course' => $course,
                'results' => $results,
                'score' => $score,
                'correctAnswers' => $correctAnswers,
                'totalQuestions' => $totalQuestions,
                'passed' => $passed,
            ] = $this->buildResultsFromTest($test);

            session(['exam_test_id' => $test->id]);
        } else {
            // Get other session data
            $score = session('exam_score');
            $correctAnswers = session('exam_correct');
            $totalQuestions = session('exam_total');
            $passed = session('exam_passed');
        }

        // Add index to results
        foreach ($results as $index => $result) {
            $results[$index]['index'] = $index;
        }

        // Apply filter if needed
        $filter = $request->get('filter', 'all');
        $filteredResults = $results;

        if ($filter === 'correct') {
            $filteredResults = array_filter($results, function ($result) {
                return $result['is_correct'] === true;
            });
        } elseif ($filter === 'wrong') {
            $filteredResults = array_filter($results, function ($result) {
                return $result['is_correct'] === false;
            });
        }

        // Reindex filtered results
        $filteredResults = array_values($filteredResults);

        // ✅ CHANGE THIS LINE - Show ONE question per page
        $perPage = 1;  // Changed from 10 to 1

        $currentPage = (int) $request->get('page', 1);
        $totalItems = count($filteredResults);
        $totalPages = max(1, ceil($totalItems / $perPage));
        $currentPage = max(1, min($currentPage, $totalPages));

        $offset = ($currentPage - 1) * $perPage;
        $paginatedResults = array_slice($filteredResults, $offset, $perPage);

        // Build pagination links with filter parameter
        $paginationLinks = [];
        for ($i = 1; $i <= $totalPages; $i++) {
            $urlParams = ['page' => $i];
            if ($filter !== 'all') {
                $urlParams['filter'] = $filter;
            }
            $paginationLinks[] = [
                'label' => $i,
                'url' => request()->url().'?'.http_build_query($urlParams),
                'active' => $i === $currentPage,
            ];
        }

        $prevPageUrl = null;
        if ($currentPage > 1) {
            $urlParams = ['page' => $currentPage - 1];
            if ($filter !== 'all') {
                $urlParams['filter'] = $filter;
            }
            $prevPageUrl = request()->url().'?'.http_build_query($urlParams);
        }

        $nextPageUrl = null;
        if ($currentPage < $totalPages) {
            $urlParams = ['page' => $currentPage + 1];
            if ($filter !== 'all') {
                $urlParams['filter'] = $filter;
            }
            $nextPageUrl = request()->url().'?'.http_build_query($urlParams);
        }

        // ✅ Check if user has AI access via subscription
        $hasAiAccess = false;
        $userSubscription = UserSubscription::where('user_id', auth()->id())
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>=', now());
            })
            ->with('plan')
            ->first();

        if ($userSubscription && $userSubscription->plan && $userSubscription->plan->aistatus == 1) {
            $hasAiAccess = true;
        }

        return view('mock.review', [
            'course' => $course,
            'results' => $results,
            'paginatedResults' => $paginatedResults,
            'score' => $score,
            'correctAnswers' => $correctAnswers,
            'totalQuestions' => $totalQuestions,
            'passed' => $passed,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'paginationLinks' => $paginationLinks,
            'prevPageUrl' => $prevPageUrl,
            'nextPageUrl' => $nextPageUrl,
            'filter' => $filter,
            'timeUsed' => '—',
            'hasAiAccess' => $hasAiAccess,
        ]);
    }

    // 🤖 AI Explanation endpoint
    public function aiExplanation(Request $request)
    {
        // Check if user has AI access
        $userSubscription = UserSubscription::where('user_id', auth()->id())
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>=', now());
            })
            ->with('plan')
            ->first();

        if (! $userSubscription || ! $userSubscription->plan || $userSubscription->plan->aistatus != 1) {
            return response()->json(['error' => 'AI access not available'], 403);
        }

        // Get question data from request
        $questionId = $request->input('question_id');
        $userAnswer = $request->input('user_answer');
        $correctAnswer = $request->input('correct_answer');

        // Fetch question from database
        $question = Question::findOrFail($questionId);

        // Build the prompt for AI
        $options = [];
        foreach (['a', 'b', 'c', 'd'] as $letter) {
            $optionKey = 'option_'.$letter;
            if ($question->$optionKey) {
                $options[strtoupper($letter)] = $question->$optionKey;
            }
        }

        $prompt = "You are a tutor explaining a multiple choice question to a student. Analyze the following:\n\n";
        $prompt .= "QUESTION: {$question->question}\n\n";
        $prompt .= "OPTIONS:\n";
        foreach ($options as $letter => $optionText) {
            $prompt .= "{$letter}. {$optionText}\n";
        }
        $prompt .= "\nSTUDENT'S ANSWER: {$userAnswer}\n";
        $prompt .= "CORRECT ANSWER: {$correctAnswer}\n\n";
        $prompt .= "Please provide:\n";
        $prompt .= "1. Why the correct answer ({$correctAnswer}) is right\n";
        $prompt .= "2. Why other options are incorrect\n";
        $prompt .= "3. Key concepts to remember\n\n";
        $prompt .= 'Be concise, clear, and educational in your explanation.';

        // Use AI service to generate explanation
        // For now, we'll use a simple HTTP client to call an AI API
        // You can replace this with your actual AI provider
        try {
            $explanation = $this->generateAiExplanation($prompt);

            return response()->json(['explanation' => $explanation]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate explanation: '.$e->getMessage()], 500);
        }
    }

    // Helper method to generate AI explanation
    private function generateAiExplanation(string $prompt): string
    {
        $apiKey = config('services.openai.api_key');
        $model = config('services.openai.model', 'gpt-3.5-turbo');
        $maxTokens = config('services.openai.max_tokens', 1000);
        $temperature = config('services.openai.temperature', 0.7);

        if (! $apiKey) {
            throw new \Exception('OpenAI API key not configured');
        }

        try {
            $client = new Client;
            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => "Bearer {$apiKey}",
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => $model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are a helpful tutor. Explain multiple choice questions clearly, explaining why the correct answer is right and why incorrect options are wrong. Keep your explanations concise and educational.',
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                    'temperature' => $temperature,
                    'max_tokens' => $maxTokens,
                ],
                'timeout' => 30,
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['choices'][0]['message']['content'])) {
                return $data['choices'][0]['message']['content'];
            }

            throw new \Exception('Invalid response from OpenAI API');
        } catch (ClientException $e) {
            $errorResponse = json_decode($e->getResponse()->getBody(), true);
            $errorMessage = $errorResponse['error']['message'] ?? $e->getMessage();
            throw new \Exception("OpenAI API Error: {$errorMessage}");
        } catch (RequestException $e) {
            throw new \Exception("Failed to connect to OpenAI API: {$e->getMessage()}");
        }
    }

    // Results page - shows all exams the user has taken
    public function results(Request $request)
    {
        $userId = auth()->id();

        // Fetch tests for the current user with course relationship
        $tests = Test::with('course')
            ->where('user_id', $userId)
            ->whereNotNull('score')
            ->where('total_questions', '>', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('mock.results', compact('tests'));
    }

    private function buildResultsFromTest(Test $test): array
    {
        $answers = $test->answers()->with('question')->get();

        $results = $answers->map(function ($answer) {
            $question = $answer->question;
            $correctAnswer = $question->answer ?? $question->correct_answer ?? '';

            return [
                'question' => $question,
                'question_id' => $question?->id,
                'user_answer' => $answer->selected_option,
                'correct_answer' => $correctAnswer,
                'is_correct' => (bool) $answer->is_correct,
                'question_type' => $question->question_type ?? null,
            ];
        })->all();

        $correctAnswers = (int) $test->score;
        $totalQuestions = (int) $test->total_questions;
        $score = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100) : 0;

        return [
            'course' => $test->course,
            'results' => $results,
            'score' => $score,
            'correctAnswers' => $correctAnswers,
            'totalQuestions' => $totalQuestions,
            'passed' => $score >= 50,
        ];
    }
}
