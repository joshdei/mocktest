<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\MockPrice;
use App\Models\UserSubscription;
use App\Models\StudentWallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaystackPlanController extends Controller
{
    public function initialize(Request $request)
    {
        // 1. Validate first
        $request->validate(['plan_id' => 'required|exists:mock_prices,id']);

        $plan = MockPrice::findOrFail($request->plan_id);
        $user = auth()->user();

        // 2. Free plans don't require payment
        if ($plan->price <= 0) {
            return redirect()->route('plan.index')->with('error', 'This plan does not require payment.');
        }

        // 3. Check for existing active, non-expired PAID subscription
        $existingSubscription = UserSubscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>=', Carbon::now());
            })
            ->first();

        if ($existingSubscription) {
            $existingPlan = MockPrice::find($existingSubscription->plan_id);
            if ($existingPlan && $existingPlan->price > 0) {
                return redirect()->route('plan.index')->with('error', 'You already have an active subscription. Please wait for it to expire before subscribing to a new plan.');
            }
        }

        // 4. Get or create wallet
        $wallet = StudentWallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0]
        );

        // 5. Check if wallet has enough balance
        if ($wallet->balance < $plan->price) {
            $shortfall = $plan->price - $wallet->balance;
            return redirect()->route('wallet')->with('error', 
                'Insufficient wallet balance. You need ₦' . number_format($shortfall, 2) . ' more to subscribe to this plan.'
            );
        }

        // 6. Deduct from wallet and create subscription in one transaction
        try {
            DB::transaction(function () use ($user, $plan, $wallet) {
                $planDuration = (int) ($plan->duration ?? 30);

                // Deduct balance from wallet
                $wallet->decrement('balance', $plan->price);

                // Find any existing active subscription
                $existingSubscription = UserSubscription::where('user_id', $user->id)
                    ->where('status', 'active')
                    ->first();

                // Check if it is still valid
                $hasValidSubscription = false;
                if ($existingSubscription && $existingSubscription->expiry_date) {
                    $hasValidSubscription = Carbon::now()->lt(
                        Carbon::parse($existingSubscription->expiry_date)
                    );
                }

                if ($hasValidSubscription) {
                    // Extend from current expiry date
                    $newExpiryDate = Carbon::parse($existingSubscription->expiry_date)
                        ->addDays($planDuration);

                    $existingSubscription->update([
                        'plan_id'     => $plan->id,
                        'expiry_date' => $newExpiryDate,
                        'status'      => 'active',
                    ]);
                } else {
                    // Mark old subscription as expired
                    if ($existingSubscription) {
                        $existingSubscription->update(['status' => 'expired']);
                    }

                    // Create fresh subscription from today
                    $startDate  = Carbon::now();
                    $expiryDate = $planDuration > 0
                        ? $startDate->copy()->addDays($planDuration)
                        : null;

                    UserSubscription::create([
                        'user_id'     => $user->id,
                        'plan_id'     => $plan->id,
                        'start_date'  => $startDate,
                        'expiry_date' => $expiryDate,
                        'status'      => 'active',
                    ]);
                }
            });

            // Get updated subscription for message
            $subscription = UserSubscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->first();

            $expiryFormatted = $subscription->expiry_date
                ? Carbon::parse($subscription->expiry_date)->format('M d, Y')
                : 'Never';

            return redirect()->route('plan.index')->with('success',
                'You have successfully subscribed to the ' . $plan->name . ' plan! Expires: ' . $expiryFormatted
            );

        } catch (\Exception $e) {
            Log::error('Wallet Plan Subscription Error: ' . $e->getMessage());
            return redirect()->route('plan.index')->with('error', 
                'Subscription failed. Please try again or contact support.'
            );
        }
    }

    // Paystack callback and cancel are no longer needed 
    // but kept to avoid route errors
    public function handleCallback(Request $request)
    {
        return redirect()->route('plan.index');
    }

    public function cancel()
    {
        return redirect()->route('plan.index')->with('error', 'Action was cancelled.');
    }
}