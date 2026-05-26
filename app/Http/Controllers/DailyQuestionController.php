<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\StudentQuestionAttempt;
use App\Models\StudentWallet;
use App\Models\WeeklyQuestionSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyQuestionController extends Controller
{
    public function show()
    {
        $user = auth()->user();

        if (! $user->hasVerifiedEmail()) {
            return redirect()->back()->with('warning', 'Verify your email to access this feature.');
        }

        $weekNumber = Carbon::now()->isoWeek();
        $year = Carbon::now()->year;

        $schedule = WeeklyQuestionSchedule::query()
            ->where('week_number', $weekNumber)
            ->where('year', $year)
            ->with('question')
            ->first();

        $todayOk = $schedule && Carbon::today()->toDateString() === $schedule->scheduled_date->toDateString();
        $questionVisible = (bool) $todayOk;

        $alreadyAnswered = false;
        $attempt = null;

        if ($schedule) {
            $attempt = StudentQuestionAttempt::query()
                ->where('user_id', Auth::id())
                ->where('week_number', $weekNumber)
                ->where('year', $year)
                ->first();
            $alreadyAnswered = (bool) $attempt;
        }

        return view('dashboard.home', compact(
            'schedule',
            'questionVisible',
            'alreadyAnswered',
            'attempt',
        ));
    }

    public function submit(Request $request)
    {
        $user = auth()->user();

        if (! $user->hasVerifiedEmail()) {
            abort(403);
        }

        $weekNumber = Carbon::now()->isoWeek();
        $year = Carbon::now()->year;

        $schedule = WeeklyQuestionSchedule::query()
            ->where('week_number', $weekNumber)
            ->where('year', $year)
            ->with('question')
            ->first();

        if (! $schedule || Carbon::today()->toDateString() !== $schedule->scheduled_date->toDateString()) {
            abort(403);
        }

        $attempt = StudentQuestionAttempt::query()
            ->where('user_id', Auth::id())
            ->where('week_number', $weekNumber)
            ->where('year', $year)
            ->first();

        if ($attempt) {
            abort(403);
        }

        $questionType = $schedule->question->question_type;

        if ($questionType === 'mcq') {
            $validated = $request->validate([
                'selected_option' => 'required|in:a,b,c,d',
            ]);
        } else {
            $validated = $request->validate([
                'selected_option' => 'required|string|max:255',
            ]);
        }

        $selectedOption = $validated['selected_option'];

        if ($questionType === 'mcq') {
            $isCorrect = ($selectedOption === $schedule->question->answer);
        } else {
            $isCorrect = (strtolower(trim($selectedOption)) === strtolower(trim($schedule->question->answer)));
        }

        $studentAttempt = StudentQuestionAttempt::create([
            'user_id' => Auth::id(),
            'weekly_question_schedule_id' => $schedule->id,
            'week_number' => $weekNumber,
            'year' => $year,
            'selected_option' => $selectedOption,
            'is_correct' => $isCorrect,
        ]);

        if ($isCorrect) {
            $wallet = StudentWallet::firstOrCreate(
                ['user_id' => Auth::id()],
                ['balance' => 0]
            );
            $wallet->increment('balance', 10);
        }

        return back()->with('correct', $isCorrect ? '🎉 Correct! ₦10 has been credited to your wallet.' : '❌ Wrong answer. Better luck next week!');
    }
}

