<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MockPrice;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class PlanController extends Controller
{
    /**
     * Display the list of available plans
     */
    public function index()
{
    $plans = MockPrice::where('status', 'active')
        ->where('name', '!=', 'Basic')
        ->orderBy('order', 'asc')
        ->get();

    $userSubscription = UserSubscription::where('user_id', Auth::id())
        ->where('status', 'active')
        ->where(function($query) {
            $query->whereNull('expiry_date')
                ->orWhere('expiry_date', '>=', Carbon::now());
        })
        ->with('plan.planDetail', 'plan.planColor')
        ->first();

    return view('plan.index', compact('plans', 'userSubscription'));
}

    
    /**
     * Subscribe to a plan
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:mock_prices,id'
        ]);
        
        $plan = MockPrice::findOrFail($request->plan_id);
        
        // Check if user already has an active subscription
        $existingSubscription = UserSubscription::where('user_id', Auth::id())
            ->where('status', 'active')
            ->where(function($query) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>=', Carbon::now());
            })
            ->first();
        
        if ($existingSubscription) {
            // User already has active subscription, redirect back with message
            return redirect()->route('plan.index')->with('error', 'You already have an active subscription. Please upgrade or renew instead.');
        }
        
        // Determine expiry date based on plan duration
        $startDate = Carbon::now();
        $expiryDate = null;
        
        if ($plan->duration && $plan->duration > 0) {
            if ($plan->durationendday && $plan->durationendday > 0) {
                // Duration is in days
                $expiryDate = $startDate->addDays($plan->durationendday);
            } else {
                // Default duration in days
                $expiryDate = $startDate->copy()->addDays($plan->duration);
            }
        }
        
        // Create new subscription
        $subscription = UserSubscription::create([
            'user_id' => Auth::id(),
            'plan_id' => $plan->id,
            'start_date' => Carbon::now(),
            'expiry_date' => $expiryDate,
            'status' => 'active'
        ]);
        
        return redirect()->route('plan.index')->with('success', 'You have successfully subscribed to the ' . $plan->name . ' plan!');
    }

    /**
     * Upgrade/Renew subscription
     */
    public function upgrade(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:mock_prices,id'
        ]);
        
        $plan = MockPrice::findOrFail($request->plan_id);
        
        // Check for existing subscription
        $existingSubscription = UserSubscription::where('user_id', Auth::id())
            ->where('status', 'active')
            ->first();
        
        if ($existingSubscription) {
            // Check if current plan is expired
            if ($existingSubscription->expiry_date && Carbon::now()->gt($existingSubscription->expiry_date)) {
                // Plan expired, create new subscription
                $startDate = Carbon::now();
                $expiryDate = null;
                
                if ($plan->durationendday && $plan->durationendday > 0) {
                    $expiryDate = $startDate->addDays($plan->durationendday);
                } elseif ($plan->duration && $plan->duration > 0) {
                    $expiryDate = $startDate->copy()->addDays($plan->duration);
                }
                
                $existingSubscription->update([
                    'plan_id' => $plan->id,
                    'start_date' => $startDate,
                    'expiry_date' => $expiryDate,
                    'status' => 'active'
                ]);
                
                return redirect()->route('plan.index')->with('success', 'Your subscription has been renewed with ' . $plan->name . ' plan!');
            } else {
                // Plan still active, extend it
                $currentExpiry = $existingSubscription->expiry_date ?? Carbon::now();
                $newExpiry = null;
                
                if ($plan->durationendday && $plan->durationendday > 0) {
                    $newExpiry = $currentExpiry->addDays($plan->durationendday);
                } elseif ($plan->duration && $plan->duration > 0) {
                    $newExpiry = $currentExpiry->copy()->addDays($plan->duration);
                }
                
                $existingSubscription->update([
                    'plan_id' => $plan->id,
                    'expiry_date' => $newExpiry,
                    'status' => 'active'
                ]);
                
                return redirect()->route('plan.index')->with('success', 'Your subscription has been upgraded to ' . $plan->name . ' plan!');
            }
        } else {
            // No existing subscription, create new one (same as subscribe)
            $startDate = Carbon::now();
            $expiryDate = null;
            
            if ($plan->durationendday && $plan->durationendday > 0) {
                $expiryDate = $startDate->addDays($plan->durationendday);
            } elseif ($plan->duration && $plan->duration > 0) {
                $expiryDate = $startDate->copy()->addDays($plan->duration);
            }
            
            UserSubscription::create([
                'user_id' => Auth::id(),
                'plan_id' => $plan->id,
                'start_date' => $startDate,
                'expiry_date' => $expiryDate,
                'status' => 'active'
            ]);
            
            return redirect()->route('plan.index')->with('success', 'You have successfully subscribed to the ' . $plan->name . ' plan!');
        }
    }
}
