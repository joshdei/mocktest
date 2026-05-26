<?php

namespace App\Http\Controllers;

use App\Mail\ChallengeInviteMail;
use App\Models\StudyChallenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ChallengeNudgeController extends Controller
{
    public function nudge(Request $request, StudyChallenge $challenge)
    {
        $user = $request->user();

        if ($challenge->status !== 'pending') {
            return back()->with('error', 'Challenge is not pending anymore.');
        }

        // Only challenger can nudge the opponent until opponent plays.
        if ((int) $challenge->challenger_id !== (int) $user->id) {
            abort(403);
        }

        $opponent = $challenge->opponent()->first();
        if (! $opponent || ! $opponent->email) {
            return back()->with('error', 'Opponent email not available.');
        }

        // If score is not ready yet, pass 0.
        $score = (int) ($challenge->challenger_score ?? 0);
        $challengerName = $user->first_name;

        Mail::to($opponent->email)->send(new ChallengeInviteMail($challenge, $challengerName, $score));

        return back()->with('success', 'Nudge sent.');
    }
}
