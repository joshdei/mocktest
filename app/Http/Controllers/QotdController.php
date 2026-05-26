<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\User_Point;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QotdController extends Controller
{
    private function requireVerified(): ?JsonResponse
    {
        $user = auth()->user();

        if (! $user || empty($user->email_verified_at)) {
            return response()->json(['error' => 'Email not verified'], 403);
        }

        return null;
    }

    /**
     * Return a random Question of the Day payload.
     */
    public function current(): JsonResponse
    {
        if ($res = $this->requireVerified()) {
            return $res;
        }

        $questionQuery = Question::query()
            ->whereNotNull('question')
            ->where('question', '!=', '');

        $bounds = (clone $questionQuery)
            ->selectRaw('MIN(id) as min_id, MAX(id) as max_id')
            ->first();

        if (! $bounds?->min_id || ! $bounds?->max_id) {
            return response()->json(['error' => 'No questions found'], 404);
        }

        $question = null;
        $minId = (int) $bounds->min_id;
        $maxId = (int) $bounds->max_id;

        for ($attempt = 0; $attempt < 3 && ! $question; $attempt++) {
            $randomId = random_int($minId, $maxId);

            $question = (clone $questionQuery)
                ->where('id', '>=', $randomId)
                ->orderBy('id')
                ->first();
        }

        $question ??= (clone $questionQuery)
            ->orderBy('id')
            ->first();

        if (! $question) {
            return response()->json(['error' => 'No questions found'], 404);
        }

        $question->load('course');

        return response()->json([
            'question_id' => $question->id,
            'course_id' => $question->course_id,
            'course_label' => collect([
                $question->course?->course_code,
                $question->course?->course_name,
            ])->filter()->implode(' - '),
            'question' => $question->question,
            'options' => [
                'A' => $question->option_a,
                'B' => $question->option_b,
                'C' => $question->option_c,
                'D' => $question->option_d,
            ],
            'question_type' => $question->question_type,
        ]);
    }

    /**
     * Submit answer and return result payload.
     */
    public function submit(Request $request): JsonResponse
    {
        if ($res = $this->requireVerified()) {
            return $res;
        }

        $request->validate([
            'question_id' => 'required|integer',
            'selected_option' => 'nullable|string',
            'answer' => 'nullable|string|max:255',
        ]);

        $question = Question::findOrFail($request->input('question_id'));

        $isMcq = $question->question_type === 'mcq';
        $request->validate([
            'selected_option' => $isMcq ? 'required|in:A,B,C,D' : 'nullable',
            'answer' => $isMcq ? 'nullable' : 'required|string|max:255',
        ]);

        $selected = $isMcq
            ? (string) $request->input('selected_option')
            : trim((string) $request->input('answer'));
        $correct = (string) $question->answer;

        $isCorrect = $isMcq
            ? strtoupper($selected) === strtoupper($correct)
            : strcasecmp(trim($selected), trim($correct)) === 0;

        // NOTE: You can later store attempts/points in DB.
        // For now we only return computed result.

        // Credit points (once per question per user per day)
        $pointsAwarded = 0;
        if ($isCorrect && $isMcq) {
            $pointsAwarded = 7;

            $userId = $request->user()->id;
            $today = now()->toDateString();

            // Create or load user points row
            $userPoints = User_Point::query()->firstOrCreate(
                ['user_id' => $userId],
                ['bonus_points' => 0, 'total_points' => 0, 'used_points' => 0]
            );

            // Prevent double crediting by storing a simple marker in cashable column set
            // Since current schema has no qotd_attempts table, we use today's date in the bonus_points column only
            // NOTE: If your schema already has an attempts table, replace this block.
            // Here we just ensure we don't add repeatedly by using a separate per-question marker in session.
            $sessionKey = 'qotd.awarded.'.$question->id.'.'.$today;

            if (! $request->session()->get($sessionKey)) {
                $userPoints->increment('bonus_points', $pointsAwarded);
                $userPoints->increment('total_points', $pointsAwarded);
                $request->session()->put($sessionKey, true);
            } else {
                $pointsAwarded = 0;
            }
        }

        return response()->json([
            'question_id' => $question->id,
            'question_type' => $question->question_type,
            'selected_option' => $isMcq ? strtoupper($selected) : $selected,
            'correct_option' => $isMcq ? strtoupper($correct) : null,
            'correct_answer' => $correct,
            'is_correct' => $isCorrect,
            'points' => $pointsAwarded,
            'message' => $isCorrect ? 'Correct!' : 'Not quite',
        ]);
    }
}
