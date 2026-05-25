<?php

namespace App\Http\Controllers;

use App\Mail\ChallengeInviteMail;
use App\Mail\ChallengeResultMail;
use App\Models\Question;
use App\Models\StudyChallenge;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class ChallengeController extends Controller
{
    public function findOpponent(Request $request)
    {
        $user = auth()->user();

        $opponent = User::query()
            ->where('id', '!=', $user->id)
            ->inRandomOrder()
            ->first();

        if (! $opponent) {
            return redirect()->route('dashboard')->with('error', 'No opponent available right now.');
        }

        // Create question_set immediately so this row is fully independent per your spec.
        // To reuse mock logic we need a course context; since this feature spec doesn't include course,
        // we’ll derive a set from a random course and its questions count similar to mock start.
        // Next improvements can make this course-specific once UI includes it.
        $courseId = Question::query()
            ->select('course_id')
            ->whereNotNull('course_id')
            ->inRandomOrder()
            ->value('course_id');

        if (! $courseId) {
            return redirect()->route('dashboard')->with('error', 'No questions available to start a challenge.');
        }

        // Choose number of questions similarly to mock plans; default to 10 if no mock plan size is available.
        $questionIds = Question::query()
            ->where('course_id', $courseId)
            ->inRandomOrder()
            ->limit(10)
            ->pluck('id')
            ->toArray();

        if (count($questionIds) === 0) {
            return redirect()->route('dashboard')->with('error', 'No questions available to start a challenge.');
        }

        $challenge = StudyChallenge::create([
            'challenger_id' => $user->id,
            'opponent_id' => $opponent->id,
            'question_set' => $questionIds,
            'challenger_score' => null,
            'opponent_score' => null,
            'status' => 'pending',
            'expires_at' => now()->addHours(48),
            'winner_id' => null,
        ]);

        return redirect()->route('dashboard');
    }

    public function sendChallenge(Request $request, StudyChallenge $challenge)
    {
        $user = auth()->user();

        if ($challenge->challenger_id !== $user->id) {
            abort(403);
        }

        if (! in_array($challenge->status, ['pending'], true)) {
            return redirect()->route('dashboard')->with('error', 'Challenge is not ready.');
        }

        return redirect()->route('challenge.play', ['challenge' => $challenge->id, 'role' => 'challenger']);
    }

    public function challengerSubmit(Request $request, StudyChallenge $challenge)
    {
        $user = auth()->user();


        if ($challenge->challenger_id !== $user->id) {
            abort(403);
        }

        if ($challenge->status !== 'pending') {
            return redirect()->route('dashboard')->with('error', 'Challenge already progressed.');
        }

        $answers = $request->input('answers', []);
        $questionIds = $challenge->question_set ?? [];
        $questions = Question::query()->whereIn('id', $questionIds)->get()->keyBy('id');

        $totalQuestions = count($questionIds);
        $correctAnswers = 0;

        foreach ($questionIds as $qid) {
            $question = $questions->get($qid);
            if (! $question) {
                continue;
            }

            $userAnswer = $answers[$qid] ?? null;

            $correctAnswer = $question->answer ?? $question->correct_answer ?? '';

            $isCorrect = false;
            if (($question->question_type ?? '') === 'mcq') {
                $isCorrect = strtoupper((string)$userAnswer) === strtoupper((string)$correctAnswer);
            } else {
                $isCorrect = strtolower(trim((string)($userAnswer ?? ''))) === strtolower(trim((string)$correctAnswer));
            }

            if ($isCorrect) {
                $correctAnswers++;
            }
        }

        $score = (int) round(($correctAnswers / max(1, $totalQuestions)) * 100);

        DB::transaction(function () use ($challenge, $score) {
            $challenge->update([
                'challenger_score' => $score,
                'status' => 'challenger_played',
            ]);
        });

        // Mail opponent with exact hook line: "[Name] scored 68% — think you can beat that?"
        $opponent = $challenge->opponent()->first();

        $challengerName = $user->first_name;

        Mail::to($opponent->email)->send(new ChallengeInviteMail($challenge, $challengerName, $score));

        return redirect()->route('dashboard');
    }

    public function opponentPlay(StudyChallenge $challenge, Request $request)
    {
        $user = auth()->user();

        $role = $request->query('role', null);
        // We render opponent view regardless; route used by email "Accept & Play" will load with role=opponent.
        if ($role !== 'opponent' || $challenge->opponent_id !== $user->id) {
            // allow only opponent to view
            abort(403);
        }

        $questionIds = $challenge->question_set ?? [];
        $questions = Question::query()
            ->whereIn('id', $questionIds)
            ->get()
            ->sortBy(fn($q) => array_search($q->id, $questionIds, true))
            ->values();

        return view('challenges.play', [
            'challenge' => $challenge,
            'questions' => $questions,
            'duration' => 60,
            'role' => 'opponent',
        ]);
    }

    public function opponentSubmit(Request $request, StudyChallenge $challenge)
    {
        $user = auth()->user();

        if ($challenge->opponent_id !== $user->id) {
            abort(403);
        }

        if (! in_array($challenge->status, ['challenger_played'], true)) {
            return redirect()->route('dashboard')->with('error', 'Challenge not awaiting you.');
        }

        $answers = $request->input('answers', []);
        $questionIds = $challenge->question_set ?? [];
        $questions = Question::query()->whereIn('id', $questionIds)->get()->keyBy('id');

        $totalQuestions = count($questionIds);
        $correctAnswers = 0;

        foreach ($questionIds as $qid) {
            $question = $questions->get($qid);
            if (! $question) {
                continue;
            }

            $userAnswer = $answers[$qid] ?? null;
            $correctAnswer = $question->answer ?? $question->correct_answer ?? '';

            $isCorrect = false;
            if (($question->question_type ?? '') === 'mcq') {
                $isCorrect = strtoupper((string)$userAnswer) === strtoupper((string)$correctAnswer);
            } else {
                $isCorrect = strtolower(trim((string)($userAnswer ?? ''))) === strtolower(trim((string)$correctAnswer));
            }

            if ($isCorrect) {
                $correctAnswers++;
            }
        }

        $score = (int) round(($correctAnswers / max(1, $totalQuestions)) * 100);

        DB::transaction(function () use ($challenge, $score, $user) {
            $challengerScore = (int) ($challenge->challenger_score ?? 0);

            $winnerId = null;
            if ($challengerScore > $score) {
                $winnerId = $challenge->challenger_id;
            } elseif ($score > $challengerScore) {
                $winnerId = $challenge->opponent_id;
            } else {
                // draw: winner_id stays null
                $winnerId = null;
            }

            $challenge->update([
                'opponent_score' => $score,
                'winner_id' => $winnerId,
                'status' => 'completed',
            ]);
        });

        Mail::to($challenge->challenger()->first()->email)
            ->send(new ChallengeResultMail($challenge));

        return redirect()->route('dashboard');
    }

    // Shared play endpoint for challenger and opponent.
    public function play(StudyChallenge $challenge, Request $request)
    {
        $user = auth()->user();
        $role = $request->query('role');

        if (! in_array($role, ['challenger', 'opponent'], true)) {
            abort(404);
        }

        if ($role === 'challenger' && $challenge->challenger_id !== $user->id) {
            abort(403);
        }

        if ($role === 'opponent' && $challenge->opponent_id !== $user->id) {
            abort(403);
        }

        // If challenger already played, they should still be able to review/see result;
        // dashboard will handle action states.
        if ($role === 'challenger' && $challenge->status !== 'pending') {
            // prevent replay from challenger side for this challenge
            return redirect()->route('dashboard');
        }

        $questionIds = $challenge->question_set ?? [];
        $questions = Question::query()
            ->whereIn('id', $questionIds)
            ->get()
            ->sortBy(fn($q) => array_search($q->id, $questionIds, true))
            ->values();

        return view('challenges.play', [
            'challenge' => $challenge,
            'questions' => $questions,
            'duration' => 60,
            'role' => $role,
        ]);
    }
}
