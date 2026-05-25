@extends('layouts.dashboard')

@section('title')
@section('page-title')

@section('dashboard-content')
<style>
:root {
  --green:       #1A6B3C;
  --green-mid:   #22844B;
  --green-light: #E8F5EE;
  --green-pale:  #F0FAF4;
  --white:       #FFFFFF;
  --off-white:   #F8F9F6;
  --gray-50:     #F9FAFB;
  --gray-100:    #F3F4F6;
  --gray-200:    #E5E7EB;
  --gray-300:    #D1D5DB;
  --gray-400:    #9CA3AF;
  --gray-500:    #6B7280;
  --gray-600:    #4B5563;
  --gray-700:    #374151;
  --text:        #1C2B1E;
  --text-muted:  #5A6B5E;
  --border:      #DDE8E1;
  --amber:       #D97706;
  --amber-light: #FEF3C7;
  --purple:      #7C3AED;
  --purple-light:#EDE9FE;
  --shadow:      rgba(26,107,60,.08);
  --shadow-md:   rgba(26,107,60,.14);
}

.setup-wrapper {
  min-height: calc(100vh - 80px);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 32px 20px;
  position: relative;
}

.setup-wrapper::before {
  content: '';
  position: fixed; inset: 0; z-index: 0;
  background-image:
    linear-gradient(rgba(26,107,60,.04) 1px, transparent 1px),
    linear-gradient(90deg, rgba(26,107,60,.04) 1px, transparent 1px);
  background-size: 48px 48px;
  pointer-events: none;
}

.setup-card {
  background: var(--white);
  border: 1.5px solid var(--border);
  border-radius: 24px;
  padding: 50px 56px;
  max-width: 680px;
  width: 100%;
  position: relative;
  z-index: 1;
  box-shadow: 0 20px 60px rgba(26,107,60,.1);
  animation: fadeUp .6s ease both;
}

@keyframes fadeUp {
  from { opacity: 0; transform: translateY(24px); }
  to   { opacity: 1; transform: none; }
}

/* Logo */
.start-logo {
  display: flex; align-items: center; gap: 11px;
  margin-bottom: 32px;
}
.logo-mark {
  width: 38px; height: 38px;
  background: var(--green); border-radius: 10px;
  display: grid; place-items: center;
  font-family: 'Playfair Display', serif;
  font-size: 18px; font-weight: 800; color: #fff;
  box-shadow: 0 4px 12px var(--shadow-md);
}
.logo-name {
  font-family: 'Playfair Display', serif;
  font-size: 1.1rem; font-weight: 700; color: var(--text);
}
.logo-name span { color: var(--green); }

/* Badge */
.exam-badge {
  display: inline-flex; align-items: center; gap: 7px;
  background: var(--green-light); border: 1.5px solid rgba(26,107,60,.2);
  color: var(--green); padding: 6px 14px; border-radius: 50px;
  font-size: .72rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
  margin-bottom: 20px;
}
.exam-badge span {
  width: 7px; height: 7px;
  background: var(--green); border-radius: 50%;
  animation: pulse 2s infinite;
}
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.3} }

.setup-title {
  font-family: 'Playfair Display', serif;
  font-size: 2rem; font-weight: 800; color: var(--text);
  margin-bottom: 8px; line-height: 1.2;
}
.course-tag { font-size: .82rem; color: var(--text-muted); margin-bottom: 28px; }
.course-tag strong { color: var(--green); }

/* Section heading */
.section-heading {
  font-size: .78rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: .08em; color: var(--gray-500);
  margin-bottom: 16px; display: block;
}

/* Plan cards */
.plans-list { display: flex; flex-direction: column; gap: 12px; margin-bottom: 28px; }

.plan-card {
  border: 1.5px solid var(--border);
  border-radius: 16px; padding: 20px 22px;
  background: var(--gray-50);
  display: flex; align-items: center; justify-content: space-between;
  gap: 16px;
  transition: all .2s;
}
.plan-card:hover {
  border-color: var(--green);
  background: var(--green-pale);
  box-shadow: 0 4px 16px var(--shadow);
}

.plan-body { flex: 1; }
.plan-name {
  font-family: 'Playfair Display', serif;
  font-size: 1.05rem; font-weight: 800; color: var(--text);
  margin-bottom: 5px;
}
.plan-desc { font-size: .82rem; color: var(--text-muted); margin-bottom: 12px; line-height: 1.5; }

.plan-notice {
  background: var(--amber-light); border: 1.5px solid rgba(217,119,6,.25);
  border-radius: 8px; padding: 8px 12px;
  font-size: .78rem; color: var(--amber);
  font-weight: 600; margin-bottom: 12px;
}

.plan-price {
  text-align: right; flex-shrink: 0;
}
.plan-price .amount {
  font-family: 'Playfair Display', serif;
  font-size: 1.8rem; font-weight: 800; color: var(--text);
  line-height: 1;
}
.plan-price .per {
  font-size: .7rem; color: var(--gray-400); font-weight: 600;
  text-transform: uppercase; letter-spacing: .06em;
}

/* Buttons */
.btn-plan {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 10px 20px; border-radius: 10px; border: none;
  font-family: 'DM Sans', sans-serif; font-size: .85rem; font-weight: 700;
  cursor: pointer; transition: all .2s; text-decoration: none;
}
.btn-plan svg { width: 16px; height: 16px; }

.btn-green  { background: var(--green);  color: #fff; box-shadow: 0 4px 12px var(--shadow-md); }
.btn-green:hover  { background: var(--green-mid); transform: translateY(-1px); }

.btn-amber  { background: var(--amber);  color: #fff; box-shadow: 0 4px 12px rgba(217,119,6,.2); }
.btn-amber:hover  { background: #B45309; transform: translateY(-1px); }

.btn-purple { background: var(--purple); color: #fff; box-shadow: 0 4px 12px rgba(124,58,237,.2); }
.btn-purple:hover { background: #6D28D9; transform: translateY(-1px); }

.btn-gray   { background: var(--gray-600); color: #fff; }
.btn-gray:hover   { background: var(--gray-700); transform: translateY(-1px); }

/* Empty state */
.empty-plans {
  background: var(--gray-50); border: 1.5px dashed var(--border);
  border-radius: 16px; padding: 32px; text-align: center;
  color: var(--text-muted); font-size: .9rem;
}

/* Info note */
.info-note {
  background: var(--green-light); border: 1.5px solid rgba(26,107,60,.2);
  border-radius: 12px; padding: 14px 18px;
  display: flex; gap: 10px; align-items: flex-start;
  margin-bottom: 20px;
}
.info-note svg { width: 18px; height: 18px; color: var(--green); flex-shrink: 0; margin-top: 1px; }
.info-note p { font-size: .8rem; color: var(--green); line-height: 1.5; margin: 0; }

.setup-back {
  display: block; text-align: center;
  font-size: .82rem; font-weight: 600; color: var(--green);
  text-decoration: none; transition: opacity .2s;
}
.setup-back:hover { opacity: .7; }

@media (max-width: 640px) {
  .setup-card { padding: 32px 24px; }
  .setup-title { font-size: 1.5rem; }
  .plan-card { flex-direction: column; align-items: flex-start; }
  .plan-price { text-align: left; }
}
</style>

<div class="setup-wrapper">
  <div class="setup-card">

    {{-- Logo --}}
    {{-- <div class="start-logo">
      <div class="logo-mark">P</div>
      <div>
        <div class="logo-name">Psalm<span>Edu</span></div>
        <div style="font-size:.6rem;color:var(--text-muted);font-weight:500;letter-spacing:.08em;text-transform:uppercase;">Mock Exam Portal</div>
      </div>
    </div> --}}

    <div class="exam-badge"><span></span> Choose Exam Plan</div>

    <h1 class="setup-title">{{ $course->course_name }}</h1>
    <p class="course-tag">Course: <strong>{{ $course->course_code }}</strong> </p>

   <span class="section-heading">How do you want to take this exam?</span>

<div class="plans-list">

  @if($userSubscription && $userSubscription->isActive())
    {{-- User has an active paid plan — just let them start --}}
    <div class="plan-card" style="border-color: var(--green); background: var(--green-pale);">
      <div class="plan-body">
        <div class="plan-name">{{ $userSubscription->plan->name }} Plan — Active</div>
        <p class="plan-desc">Your subscription covers this exam. No extra charge applies.</p>
        <form method="GET" action="{{ route('mock.setup2', $course->id) }}">
          @csrf
          <input type="hidden" name="plan_id" value="{{ $userSubscription->plan_id }}">
          <button type="submit" class="btn-plan btn-green">
            📝 Start Exam Now
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
          </button>
        </form>
      </div>
      <div class="plan-price">
        <div class="amount" style="color:var(--green);">✓</div>
        <div class="per">subscribed</div>
      </div>
    </div>

    @else
  {{-- No active plan — show free trial + basic + upgrade options --}}

  {{-- Free Trial Card --}}
  <div class="plan-card">
    <div class="plan-body">
      <div class="plan-name">Try for Free</div>
      <p class="plan-desc">Get a feel for the exam with 3 randomly selected questions. No wallet deduction.</p>
      <form method="GET" action="{{ route('mock.setup2', $course->id) }}">
        @csrf
        <input type="hidden" name="mode" value="free">
        <button type="submit" class="btn-plan btn-green">
          Try 3 Questions Free
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
          </svg>
        </button>
      </form>
    </div>
    <div class="plan-price">
      <div class="amount" style="color:var(--green);">₦0</div>
      <div class="per">3 questions</div>
    </div>
  </div>

  {{-- Basic Plan Card --}}
@php $basicPlan = $plans->first(fn($p) => strtolower($p->name) === 'basic'); @endphp
  @if($basicPlan)
    <div class="plan-card">
      <div class="plan-body">
        <div class="plan-name">{{ $basicPlan->name }} Plan</div>
        <p class="plan-desc">Access the full question set for this course with a single attempt. Pay once, no subscription needed.</p>
        <div class="plan-notice">₦{{ number_format($basicPlan->price) }} will be deducted from your wallet.</div>
        <form method="POST" action="{{ route('mock.charge', ['courseId' => $course->id, 'planId' => $basicPlan->id]) }}">
          @csrf
          <button type="submit" class="btn-plan btn-amber">
            Pay & Start with {{ $basicPlan->name }}
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
          </button>
        </form>
      </div>
      <div class="plan-price">
        <div class="amount">₦{{ number_format($basicPlan->price) }}</div>
        <div class="per">per attempt</div>
      </div>
    </div>
  @endif

  {{-- Premium/Gold and other plans (subscribe) --}}
@foreach($plans->filter(fn($p) => !in_array(strtolower($p->name), ['default', 'subscription', 'basic', 'free'])) as $plan)
    <div class="plan-card">
      <div class="plan-body">
        <div class="plan-name">{{ $plan->name }} Plan</div>

        @if(strtolower($plan->name) === 'premium' || strtolower($plan->name) === 'gold')
          <p class="plan-desc">Unlimited exams across all courses, full result explanations, and priority support. Best value.</p>
          <a href="{{ route('plan.index') }}" class="btn-plan btn-purple">
            Subscribe to {{ $plan->name }}
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
          </a>

        @else
          <p class="plan-desc">{{ $plan->description ?? 'Access this exam with a single attempt.' }}</p>
          @if($plan->price > 0)
            <div class="plan-notice">₦{{ number_format($plan->price) }} will be deducted from your wallet.</div>
          @endif
          <form method="GET" action="{{ route('mock.setup2', $course->id) }}">
            @csrf
            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
            <input type="hidden" name="mode" value="paid">
            <button type="submit" class="btn-plan btn-gray">
              Choose {{ $plan->name }}
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
              </svg>
            </button>
          </form>
        @endif
      </div>

      {{-- Hide price for premium/gold since they link to subscribe, not pay-per-attempt --}}
      @if($plan->price > 0 && !in_array(strtolower($plan->name), ['premium', 'gold']))
        <div class="plan-price">
          <div class="amount">₦{{ number_format($plan->price) }}</div>
          <div class="per">per attempt</div>
        </div>
      @endif

    </div>
@endforeach

@endif

</div>
    <div class="info-note">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 110 20A10 10 0 0112 2z"/></svg>
      <p><strong>Note:</strong> Ensure your wallet has sufficient funds before starting. Your subscription is checked automatically when you begin.</p>
    </div>

    <a href="{{ route('mock.index') }}" class="setup-back">← Back to Courses</a>

  </div>
</div>
@endsection