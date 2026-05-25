@extends('layouts.dashboard')

@section('title')
@section('page-title')

@section('dashboard-content') 


<div class="plans-page">
    <div class="page-header">
        <h1 class="page-title">Choose Your Plan</h1>
        <p class="page-subtitle">Select the perfect plan to access mock exams, past questions, and more.</p>
    </div>

 

    <!-- Current Subscription Info -->
    @if($userSubscription)
        <div class="current-plan-card">
            <div class="current-plan-info">
                <div class="current-plan-icon">
                    📋
                </div>
<div class="current-plan-details">
                    <h3>Current Plan: {{ $userSubscription->plan->name ?? 'Premium' }}</h3>
                    <p>
                        @php
                            $isFreePlan = isset($userSubscription->plan->price) && $userSubscription->plan->price == 0;
                        @endphp
                        @if($isFreePlan)
                            Unlimited access
                        @elseif($userSubscription->expiry_date)
                            Expires: {{ $userSubscription->expiry_date->format('M d, Y') }}
                            {{-- @if(Carbon\Carbon::parse($userSubscription->expiry_date)->diffInDays(Carbon\Carbon::now()) < 7 && Carbon\Carbon::parse($userSubscription->expiry_date)->gte(Carbon\Carbon::now()))
                                ({{ Carbon\Carbon::parse($userSubscription->expiry_date)->diffInDays(Carbon\Carbon::now()) }} days left)
                            @endif --}}
                        @else
                            Unlimited access
                        @endif
                    </p>
                </div>
            </div>
           @php
    $now = \Carbon\Carbon::now();
    $expiryDate = $userSubscription->expiry_date 
        ? \Carbon\Carbon::parse($userSubscription->expiry_date) 
        : null;

    $isExpired      = $expiryDate && $now->gt($expiryDate);
    $daysLeft       = $expiryDate && !$isExpired ? (int) $now->diffInDays($expiryDate) : 0;
    $isExpiringSoon = $expiryDate && !$isExpired && $daysLeft < 7;
@endphp

<span class="plan-status {{ $isExpired ? 'expired' : ($isExpiringSoon ? 'expiring' : 'active') }}">
    @if($isExpired)
        ⏸ Expired
    @elseif($isExpiringSoon)
        ⚠ Expiring Soon ({{ $daysLeft }} day{{ $daysLeft == 1 ? '' : 's' }} left)
    @else
        ✓ Active
        @if($expiryDate)
            · Expires {{ $expiryDate->format('M d, Y') }}
        @endif
    @endif
</span>
        </div>
    @else
        <div class="no-plan-msg">
            <h3>You don't have an active plan</h3>
            <p>Subscribe to a plan to access all features including mock exams and premium content.</p>
        </div>
    @endif

    <!-- Plans Grid -->
    <div class="plans-grid">
        @foreach($plans as $plan)
            @php
                $planDetail = isset($plan->planDetail) ? $plan->planDetail : null;
                $planColor = isset($plan->planColor) ? $plan->planColor : null;
                
// Determine if this is the current plan
                $isCurrentPlan = $userSubscription && $userSubscription->plan_id == $plan->id;
                $isExpired = $userSubscription && $userSubscription->expiry_date && Carbon\Carbon::now()->gt($userSubscription->expiry_date);
                $isFreePlan = isset($plan->price) && $plan->price == 0;
                
                // Button state
                $btnText = 'Subscribe';
                $btnClass = 'subscribe';
                $btnDisabled = false;
                
                if ($isCurrentPlan && !$isExpired) {
                    $btnText = 'Current Plan';
                    $btnClass = 'current';
                    $btnDisabled = true;
                } elseif ($isFreePlan && $userSubscription) {
                    // Free plans cannot be upgraded to - disable button
                    $btnText = 'Free Plan';
                    $btnClass = 'current';
                    $btnDisabled = true;
                } elseif ($userSubscription && $isExpired) {
                    $btnText = 'Renew Plan';
                    $btnClass = 'expired';
                } elseif ($userSubscription) {
                    $btnText = 'Upgrade';
                    $btnClass = 'upgrade';
                }
            @endphp
            
            <div class="plan-card {{ $loop->index == 1 ? 'featured' : '' }}">
                <div class="plan-header">
                    <h3 class="plan-name">{{ $plan->name }}</h3>
                    <div class="plan-price">
                        <span class="currency">₦</span>
                        <span class="amount">{{ number_format($plan->price) }}</span>
                         
                        
                       {{-- <p> duration<span class="period">/ {{ $plan->durationendday ? $plan->durationendday . ' days' : 'month' }}</span>
                   </p>  --}}
                
                </div>
@if($plan->price == 0)
                        <p class="plan-duration">Unlimited access</p>
                    @elseif($plan->durationendday)
                        {{-- <p class="plan-duration">{{ $plan->durationendday }} days access</p> --}}
                    @endif
                </div>

                <div class="plan-features">
                    <ul>
                        @if($planDetail)
                            @php
                                $features = is_array($planDetail->features) ? $planDetail->features : json_decode($planDetail->features, true);
                                if (!is_array($features)) {
                                    $features = array_filter(array_map('trim', explode(',', $planDetail->features ?? '')));
                                }
                            @endphp
                            @foreach($features as $feature)
                                <li>
                                    <span class="check">✓</span>
                                    {{ trim($feature) }}
                                </li>
                            @endforeach
                        @else
                            <li><span class="check">✓</span> Access to mock exams</li>
                            <li><span class="check">✓</span> View results</li>
                            @if($plan->price > 0)
                                <li><span class="check">✓</span> Premium content access</li>
                            @else
                                <li><span class="cross">✗</span> Premium content</li>
                            @endif
                        @endif
                    </ul>
                </div>

                @if($btnDisabled)
                    <button class="plan-action {{ $btnClass }}" disabled>
                        {{ $btnText }}
                    </button>
                @else
                    <form action="{{ route('paystack.plan.initialize') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        <button type="submit" class="plan-action {{ $btnClass }}">
                            {{ $btnText }}
                        </button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection

