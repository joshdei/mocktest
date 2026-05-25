<?php

namespace App\Http\Controllers;

use App\Models\Question;
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

        $question = Question::query()
            ->with('course')
            ->whereNotNull('question')
            ->inRandomOrder()
            ->first();

        if (! $question) {
            return response()->json(['error' => 'No questions found'], 404);
        }

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

        return response()->json([
            'question_id' => $question->id,
            'question_type' => $question->question_type,
            'selected_option' => $isMcq ? strtoupper($selected) : $selected,
            'correct_option' => $isMcq ? strtoupper($correct) : null,
            'correct_answer' => $correct,
            'is_correct' => $isCorrect,
            'points' => $isCorrect ? 5 : 0,
            'message' => $isCorrect ? 'Correct!' : 'Not quite',
        ]);
    }
}
