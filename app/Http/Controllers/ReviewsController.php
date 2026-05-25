<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required_if:guest,true|nullable|string|max:100',
            'rating'  => 'required|integer|min:1|max:5',
            'message' => 'required|string|max:1000',
        ]);

        if (Auth::check()) {
            // Logged-in user: one review, update if exists
            Review::updateOrCreate(
                ['user_id' => Auth::id()],
                [
                    'name'    => Auth::user()->first_name . ' ' . Auth::user()->last_name,
                    'rating'  => $request->rating,
                    'message' => $request->message,
                ]
            );
        } else {
            // Guest: always create a new review, name required
            $request->validate([
                'name' => 'required|string|max:100',
            ]);

            Review::create([
                'user_id' => null,
                'name'    => $request->name,
                'rating'  => $request->rating,
                'message' => $request->message,
            ]);
        }

        return back()->with('success', 'Thank you for your review!');
    }
}