@extends('layouts.dashboard')

@section('title')
@section('page-title')
<link href="{{ asset('css/dashboardstyle.css') }}" rel="stylesheet">
@section('dashboard-content')

<!-- ── WELCOME STRIP ── -->
<div class="welcome-strip">
  <div class="ws-text">
    <h2>Welcome back, {{ auth()->user()->first_name }} 👋</h2>
    <p>Here is what's happening with your studies today.</p>
  </div>
  {{-- <a href="{{ route('mock') }}" class="ws-cta">💳₦{{ number_format($wallet->balance ?? 0, 2) }}</a> --}}
    <a href="{{ route('mock.index') }}" class="ws-cta">Take a Test</a>
</div>
<!-- 🔥 Streak Banner Component -->
<!-- Drop the <style> block into your main CSS file and the HTML into your Blade template -->

<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500&display=swap');

/* ====== Base (mobile-first) ====== */
:root {
  --home-gap: clamp(10px, 2vw, 18px);
}

/* Prevent horizontal scrolling from long inline content */
html, body { overflow-x: hidden; }

/* Ensure the main content stretches without causing overflow */
.welcome-strip,
.quick-row,
.calendar-grid,
.events-list,
.review-ticker-wrap,
.reviews-scroll-wrapper,
.calendar-events,
.calendar-mini {
  max-width: 100%;
}

/* Welcome Strip (responsive) */
.welcome-strip {
  display: flex;
  flex-direction: column;
  gap: var(--home-gap);
  align-items: flex-start;
  padding: clamp(12px, 2vw, 18px);
  border-radius: 14px;
  box-sizing: border-box;
}

.welcome-strip .ws-text {
  width: 100%;
}

.welcome-strip h2 {
  font-family: 'Syne', sans-serif;
  font-weight: 800;
  font-size: clamp(1.1rem, 3.3vw, 1.35rem);
  margin: 0;
  line-height: 1.2;
}

.welcome-strip p {
  margin: 6px 0 0;
  font-size: clamp(0.85rem, 2.4vw, 0.95rem);
  line-height: 1.35;
  color: rgba(17,24,39,0.8);
}

.welcome-strip .ws-cta {
  width: 100%;
  box-sizing: border-box;
  text-align: center;
  text-decoration: none;
}

/* Streak */
.streak-wrap {
  padding: 1.2rem 0;
  font-family: 'DM Sans', sans-serif;
}

.streak-card {
  position: relative;
  overflow: hidden;
  border-radius: 20px;
  background: linear-gradient(135deg, #FF6B1A 0%, #FF3D00 50%, #D62800 100%);
  padding: 1.25rem 1rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  box-shadow: 0 8px 32px rgba(255, 80, 0, 0.35);
  border: none;
  gap: 12px;
}

.streak-card::before {
  content: '';
  position: absolute;
  top: -40%;
  right: -10%;
  width: 280px;
  height: 280px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.06);
  pointer-events: none;
}

.streak-card::after {
  content: '';
  position: absolute;
  bottom: -50%;
  left: 20%;
  width: 200px;
  height: 200px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.04);
  pointer-events: none;
}

.s-left {
  display: flex;
  align-items: center;
  gap: 12px;
  z-index: 1;
  min-width: 0;
}

.fire-icon {
  font-size: clamp(30px, 8vw, 48px);
  line-height: 1;
  filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.2));
  animation: flame 1.8s ease-in-out infinite;
  transform-origin: bottom center;
}

@keyframes flame {
  0%, 100% { transform: scaleY(1) rotate(-2deg); }
  33%       { transform: scaleY(1.06) rotate(1deg); }
  66%       { transform: scaleY(0.96) rotate(-1deg); }
}

.s-text {
  min-width: 0;
}

.s-text h3 {
  font-family: 'Syne', sans-serif;
  font-weight: 800;
  font-size: clamp(1rem, 3.8vw, 1.25rem);
  color: #fff;
  margin: 0 0 3px;
  letter-spacing: -0.3px;
  line-height: 1.1;
}

.s-text p {
  font-size: clamp(0.78rem, 2.2vw, 0.9rem);
  color: rgba(255, 255, 255, 0.82);
  margin: 0;
  font-weight: 400;
  letter-spacing: 0.1px;
}

.s-right {
  z-index: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: rgba(255, 255, 255, 0.14);
  border: 1.5px solid rgba(255, 255, 255, 0.28);
  border-radius: 14px;
  padding: 10px 14px 8px;
  backdrop-filter: blur(4px);
  min-width: 68px;
  flex-shrink: 0;
}

.badge-num {
  font-family: 'Syne', sans-serif;
  font-weight: 800;
  font-size: clamp(1.75rem, 6vw, 2.25rem);
  color: #fff;
  line-height: 1;
  letter-spacing: -2px;
}

.badge-lbl {
  font-size: clamp(10px, 2.3vw, 11px);
  font-weight: 500;
  color: rgba(255, 255, 255, 0.72);
  letter-spacing: 1.2px;
  text-transform: uppercase;
  margin-top: 1px;
}

.streak-dots {
  display: flex;
  gap: 5px;
  margin-top: 10px;
  flex-wrap: wrap;
}

.streak-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.35);
}

.streak-dot.active {
  background: #fff;
  box-shadow: 0 0 6px rgba(255, 255, 255, 0.6);
}

/* ===== Quick Actions: 2 cols mobile, 4 cols desktop ===== */
.quick-row {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 12px;
  width: 100%;
  box-sizing: border-box;
}

.quick-row .qa-btn {
  width: 100%;
  box-sizing: border-box;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}

/* ===== Calendar grid: 1 col mobile, 2 col desktop ===== */
.calendar-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 16px;
  width: 100%;
  box-sizing: border-box;
}

/* Inputs/buttons width: 100% (cover common ones in this file) */
input, select, textarea, button {
  box-sizing: border-box;
  max-width: 100%;
}

/* Ensure touch targets fit */
button, .qa-btn {
  -webkit-tap-highlight-color: transparent;
}

/* Review ticker hidden on <480px */
@media (max-width: 479px) {
  .review-ticker-wrap { display: none !important; }
}

/* ===== Breakpoint 480px ===== */
@media (min-width: 480px) {
  .welcome-strip {
    padding: clamp(14px, 2vw, 20px);
  }

  .quick-row {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

/* ===== Breakpoint 768px ===== */
@media (min-width: 768px) {
  .welcome-strip {
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
  }

  .welcome-strip .ws-cta {
    width: auto;
    flex-shrink: 0;
  }

  .calendar-grid {
    grid-template-columns: 1fr 1fr;
    align-items: start;
  }

  .quick-row {
    grid-template-columns: repeat(4, minmax(0, 1fr));
  }
}

/* ===== Breakpoint 1024px ===== */
@media (min-width: 1024px) {
  .quick-row {
    grid-template-columns: repeat(4, minmax(0, 1fr));
  }

  .streak-card {
    padding: 1.5rem 1.75rem;
  }

  .s-text h3 { font-size: clamp(1.1rem, 2.1vw, 1.45rem); }
}
</style>


<!-- HTML (Blade) -->

<div class="streak-wrap">
  <div class="streak-card">

    <div class="s-left">
      <div class="fire-icon">🔥</div>
      <div class="s-text">
        <h3>{{ $streakData['current_streak'] }} Days In a Row!</h3>
        <p>Keep up the momentum 💪</p>

        <!-- Dot tracker: fill 'active' class for earned days -->
        <div class="streak-dots">
          @foreach($streakData['last_7_days'] as $dayLogged)
            <div class="streak-dot {{ $dayLogged ? 'active' : '' }}"></div>
          @endforeach
        </div>
      </div>
    </div>

    <div class="s-right">
      <span class="badge-num">{{ $streakData['current_streak'] }}</span>
      <span class="badge-lbl">days</span>
    </div>

  </div>
</div>


<!-- Reviews Ticker -->
@if($reviews->count() > 0)
<div class="review-ticker-wrap">
  {{-- <div class="review-ticker-label">⭐ What students say</div> --}}
  <div class="review-ticker-track" id="reviewTicker">

    {{-- Original --}}
    @foreach($reviews as $review)
    <div class="review-ticker-item">
      <div class="review-ticker-stars">
        @for($i = 1; $i <= 5; $i++)
          <span class="{{ $i <= $review->rating ? 'star-filled' : 'star-empty' }}">★</span>
        @endfor
      </div>
      <p class="review-ticker-msg">"{{ Str::limit($review->message, 80) }}"</p>
      <span class="review-ticker-meta">
        @php
          $parts = explode(' ', trim($review->name ?? 'Anonymous'));
          echo collect($parts)->map(function($p) {
            if(strlen($p) <= 2) return $p;
            return strtoupper($p[0]) . str_repeat('*', strlen($p) - 2) . strtoupper($p[strlen($p) - 1]);
          })->implode(' ');
        @endphp
        · {{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}
      </span>
    </div>
    @endforeach

    {{-- Duplicate for seamless loop --}}
    @foreach($reviews as $review)
    <div class="review-ticker-item">
      <div class="review-ticker-stars">
        @for($i = 1; $i <= 5; $i++)
          <span class="{{ $i <= $review->rating ? 'star-filled' : 'star-empty' }}">★</span>
        @endfor
      </div>
      <p class="review-ticker-msg">"{{ Str::limit($review->message, 80) }}"</p>
      <span class="review-ticker-meta">
        @php
          $parts = explode(' ', trim($review->name ?? 'Anonymous'));
          echo collect($parts)->map(function($p) {
            if(strlen($p) <= 2) return $p;
            return strtoupper($p[0]) . str_repeat('*', strlen($p) - 2) . strtoupper($p[strlen($p) - 1]);
          })->implode(' ');
        @endphp
        · {{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}
      </span>
    </div>
    @endforeach

  </div>
</div>
@endif

<style>
  /* Streak Section */
  .streak-strip {
    background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
    border: none;
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    position: relative;
    overflow: hidden;
  }

  .streak-strip::before {
    content: '';
    position: absolute;
    top: 0;
    right: -50px;
    width: 200px;
    height: 200px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    animation: pulse-glow 3s ease-in-out infinite;
  }

  @keyframes pulse-glow {
    0%, 100% { transform: scale(1); opacity: 0.5; }
    50% { transform: scale(1.1); opacity: 0.8; }
  }

  .streak-content {
    display: flex;
    align-items: center;
    gap: 12px;
    z-index: 1;
  }

  .streak-fire {
    font-size: 2.5rem;
    animation: bounce-fire 1.5s ease-in-out infinite;
  }

  @keyframes bounce-fire {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
  }

  .streak-info h3 {
    color: white;
    font-size: 1.1rem;
    font-weight: 700;
    margin: 0;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  }

  .streak-info p {
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.85rem;
    margin: 4px 0 0;
  }

  .streak-badge {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 12px;
    padding: 10px 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    z-index: 1;
  }

  .badge-number {
    font-size: 1.5rem;
    font-weight: 800;
    color: white;
  }

  .badge-label {
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.8);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
  }

  /* Responsive */
  @media (max-width: 640px) {
    .streak-fire {
      font-size: 2rem;
    }

    .streak-info h3 {
      font-size: 0.95rem;
    }

    .streak-badge {
      padding: 8px 12px;
    }

    .badge-number {
      font-size: 1.25rem;
    }
  }

  /* Review Ticker */
.review-ticker-wrap {
  display: flex;
  align-items: center;
  gap: 12px;
  overflow: hidden;
  background: #fffbeb;
  border: 1px solid #fde68a;
  border-radius: 10px;
  padding: 10px 16px;
  margin-bottom: 1.5rem;
  position: relative;
  width: 100%;
  box-sizing: border-box;
}

.review-ticker-label {
  font-size: 0.78rem;
  font-weight: 700;
  color: #92400e;
  white-space: nowrap;
  flex-shrink: 0;
  padding-right: 12px;
  border-right: 1px solid #fde68a;
}

.review-ticker-track {
  display: flex;
  gap: 40px;
  animation: tickerScroll 25s linear infinite;
  white-space: nowrap;
  will-change: transform;
}

.review-ticker-track:hover {
  animation-play-state: paused;
}

@keyframes tickerScroll {
  0%   { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}

.review-ticker-item {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  flex-shrink: 0;
}

.review-ticker-stars {
  font-size: 0.8rem;
  flex-shrink: 0;
}

.star-filled { color: #D97706; }
.star-empty  { color: #e2e8f0; }

.review-ticker-msg {
  font-size: 0.82rem;
  color: #78350f;
  margin: 0;
  font-style: italic;
  max-width: 280px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.review-ticker-meta {
  font-size: 0.75rem;
  color: #92400e;
  font-weight: 600;
  white-space: nowrap;
  flex-shrink: 0;
}
</style>
<!-- ── QUICK ACTIONS ── -->
<div class="quick-row">
  {{-- <a href="{{ route('store.summaries') }}" class="qa-btn">
    <span class="qa-icon">📄</span>
    <div class="qa-label">Summary</div>
    <div class="qa-sub">Read condensed notes</div>
  </a>
  <a href="{{ route('store.past-questions') }}" class="qa-btn">
    <span class="qa-icon">📝</span>
    <div class="qa-label">Past Questions</div>
    <div class="qa-sub">Practice old exams</div>
  </a>
  <a href="{{ route('store.materials') }}" class="qa-btn">
    <span class="qa-icon">📚</span>
    <div class="qa-label">Materials</div>
    <div class="qa-sub">Course handouts</div>
  </a> --}}


    {{-- <a href="{{ route('mock.index') }}" class="qa-btn">
    <span class="qa-icon">📥</span>
    <div class="qa-label">Exam</div>
    <div class="qa-sub">Take CBT tests</div>
  </a>

   --}}

</div>

   <!-- Performance + Topic Performance -->
     

      <!-- Upcoming Events + Streak Row -->
       <!-- Performance + Topic Performance -->
     
@include('parties.performance')
  

<!-- ── FULL CALENDAR SECTION ── -->
<section class="calendar-section" id="calendar">
  <div class="card">
    <div class="calendar-grid">
      <!-- Events List -->
      <div class="calendar-events">
        <div class="calendar-header">
          <h3>📅 Upcoming Events</h3>
          <select class="calendar-filter" id="eventTypeFilter" onchange="filterEvents(this.value)">
            <option value="all">All Events</option>
            <option value="exam">📝 Exams</option>
            <option value="registration">📋 Registration</option>
            <option value="semester">🎓 Semester</option>
            <option value="deadline">⏰ Deadline</option>
            <option value="holiday">🎉 Holiday</option>
          </select>
        </div>

        <div class="events-list" id="eventsList">
          @php $eventTypes = ['exam','registration','semester','deadline','holiday']; @endphp
          @forelse($calendar as $event)
          @php
            $s = \Carbon\Carbon::parse($event->start_date);
            $e = \Carbon\Carbon::parse($event->end_date);
            $now = now();
            $days = $s->diffInDays($e) + 1;

            if($now->between($s, $e)) {
              $statusText = 'Active'; $statusClass = 'status-active';
            } elseif($now->lt($s)) {
              $statusText = 'Upcoming'; $statusClass = 'status-upcoming';
            } else {
              $statusText = 'Ended'; $statusClass = 'status-ended';
            }

            $titleLower = strtolower($event->event ?? '');
            $detectedType = 'general';
            foreach($eventTypes as $et) {
              if(strpos($titleLower, $et) !== false) { $detectedType = $et; break; }
            }
          @endphp
          <div class="event-item" data-type="{{ $detectedType }}">
            <div class="event-date">
              <span class="event-day">{{ $s->format('d') }}</span>
              <span class="event-month">{{ $s->format('M') }}</span>
            </div>
            <div class="event-details">
              <h4>{{ $event->event ?? 'Event' }}</h4>
              <p>
                @if($s->toDateString() == $e->toDateString())
                  {{ $s->format('F j, Y') }}
                @else
                  {{ $s->format('F j') }} – {{ $e->format('F j, Y') }}
                @endif
              </p>
              <div class="event-meta">
                <span class="event-duration">📅 {{ $days }} day{{ $days > 1 ? 's' : '' }}</span>
                <span class="event-status {{ $statusClass }}">{{ $statusText }}</span>
              </div>
            </div>
          </div>
          @empty
          <div class="event-empty">
            <div class="event-empty-icon">📭</div>
            <p>No upcoming events at the moment. Check back soon!</p>
          </div>
          @endforelse
        </div>
      </div>

      <!-- Mini Calendar -->
      <div class="calendar-mini">
        <div class="mini-calendar-header">
          <button class="month-nav" onclick="changeMonth(-1)">←</button>
          <h3 id="currentMonthYear"></h3>
          <button class="month-nav" onclick="changeMonth(1)">→</button>
        </div>
        <div class="mini-calendar-weekdays">
          <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span>
        </div>
        <div class="mini-calendar-days" id="calendarDays"></div>

        <div class="calendar-legend">
          <div class="legend-item"><span class="legend-dot" style="background:var(--green)"></span> Today</div>
          <div class="legend-item"><span class="legend-dot" style="background:#2563EB"></span> Event</div>
          <div class="legend-item"><span class="legend-dot" style="background:#D97706"></span> Upcoming</div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ── REVIEWS & CONTACT SECTION ── -->
<section class="calendar-section" id="reviews">
  <div class="card">
    <div class="calendar-grid">
      <!-- Reviews Panel -->
<div class="calendar-events">
  <div class="calendar-header">
    <h3>⭐ Reviews</h3>
    <span class="review-rating-badge">
      {{ $reviews->count() > 0 ? number_format($reviews->avg('rating'), 1) : '0.0' }} / 5
      &nbsp;({{ $reviews->count() }} {{ Str::plural('review', $reviews->count()) }})
    </span>
  </div>

  <!-- Scrolling Reviews List -->
  @if($reviews->count() > 0)
  <div class="reviews-scroll-wrapper">
    <div class="reviews-scroll-track" id="reviewsList">

      {{-- Original items --}}
      @foreach($reviews as $review)
      <div class="event-item">
        <div class="event-date">
          <span class="event-day">{{ \Carbon\Carbon::parse($review->created_at)->format('d') }}</span>
          <span class="event-month">{{ \Carbon\Carbon::parse($review->created_at)->format('M') }}</span>
        </div>
        <div class="event-details">
          <h4>{{ $review->name ?? 'Anonymous' }}
            <span class="review-stars">
              @for($i = 1; $i <= 5; $i++)
                <span class="{{ $i <= $review->rating ? 'star-filled' : 'star-empty' }}">★</span>
              @endfor
            </span>
          </h4>
          <p>{{ $review->message }}</p>
          <div class="event-meta">
            <span class="event-duration">📝 {{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</span>
            <span class="event-status status-active">Verified</span>
          </div>
        </div>
      </div>
      @endforeach

      {{-- Duplicate for seamless loop --}}
    @foreach($reviews as $review)
<div class="event-item">
  <div class="event-date">
    <span class="event-day">{{ \Carbon\Carbon::parse($review->created_at)->format('d') }}</span>
    <span class="event-month">{{ \Carbon\Carbon::parse($review->created_at)->format('M') }}</span>
  </div>
  <div class="event-details">
    <h4>
      @php
        $fullName = $review->name ?? 'Anonymous';
        $parts = explode(' ', trim($fullName));
        $masked = collect($parts)->map(function($part) {
          if(strlen($part) <= 2) return $part;
          return strtoupper($part[0]) . str_repeat('*', strlen($part) - 2) . strtoupper($part[strlen($part) - 1]);
        })->implode(' ');
      @endphp
      {{ $masked }}
      <span class="review-stars">
        @for($i = 1; $i <= 5; $i++)
          <span class="{{ $i <= $review->rating ? 'star-filled' : 'star-empty' }}">★</span>
        @endfor
      </span>
    </h4>
    <p>{{ $review->message }}</p>
    <div class="event-meta">
      <span class="event-duration">📝 {{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</span>
      <span class="event-status status-active">Verified</span>
    </div>
  </div>
</div>
@endforeach

    </div>
  </div>
  @else
  <div class="event-empty">
    <div class="event-empty-icon">💬</div>
    <p>No reviews yet. Be the first to leave one!</p>
  </div>
  @endif

  <!-- Divider -->
  <div style="border-top: 1px dashed #e2e8f0; margin: 1rem 0;"></div>



  <!-- Leave / Update Review Form -->
  @php
    $myReview = Auth::check() ? $reviews->firstWhere('user_id', Auth::id()) : null;
    $ratingLabels = ['', 'Terrible', 'Poor', 'Average', 'Good', 'Excellent'];
  @endphp

  <form class="review-form" method="POST" action="{{ route('reviews.store') }}">
    @csrf

    <h4 class="review-form-title">
      {{ $myReview ? '✏️ Update Your Review' : '✏️ Leave a Review' }}
    </h4>

    {{-- Guest: name input --}}
    @guest
    <input
      type="text"
      name="name"
      placeholder="Your name"
      value="{{ old('name') }}"
      required
    />
    @endguest

    {{-- Auth: show who is reviewing --}}
    @auth
    <p class="review-author-hint">
      Reviewing as <strong>{{ Auth::user()->first_name }}</strong>
      @if($myReview)
        &nbsp;·&nbsp;
        <span style="color:#D97706; font-size:0.78rem;">
          you already reviewed — submitting will update it
        </span>
      @endif
    </p>
    @endauth

    <!-- Star Picker -->
    <div class="star-picker-wrap">
      <p class="c-label">Your Rating</p>
      <div class="star-picker" id="starPicker">
        @for($i = 1; $i <= 5; $i++)
          <span class="star {{ $myReview && $myReview->rating >= $i ? 'active' : '' }}" data-value="{{ $i }}">★</span>
        @endfor
      </div>
      <input type="hidden" name="rating" id="ratingInput" value="{{ $myReview->rating ?? old('rating', 0) }}" />
      <p class="star-label-text" id="starLabelText">
        {{ $myReview ? $ratingLabels[$myReview->rating] : 'Tap a star to rate' }}
      </p>
    </div>

    <textarea
      name="message"
      placeholder="Write your review here..."
      rows="3"
      required
    >{{ $myReview->message ?? old('message') }}</textarea>

    <button type="submit" class="review-submit-btn">
      {{ $myReview ? 'Update Review →' : 'Submit Review →' }}
    </button>

  </form>

</div>


{{-- ── CSS ── --}}
@push('styles')
<style>
  .review-rating-badge {
    font-size: 0.8rem;
    color: #D97706;
    font-weight: 600;
  }

  .review-stars { margin-left: 6px; font-size: 0.85rem; }
  .star-filled  { color: #D97706; }
  .star-empty   { color: #e2e8f0; }

  /* Scrolling wrapper */
  .reviews-scroll-wrapper {
    overflow: hidden;
    height: 220px;
    position: relative;
    mask-image: linear-gradient(to bottom, transparent 0%, black 10%, black 90%, transparent 100%);
    -webkit-mask-image: linear-gradient(to bottom, transparent 0%, black 10%, black 90%, transparent 100%);
  }

  .reviews-scroll-track {
    display: flex;
    flex-direction: column;
    animation: scrollReviews 18s linear infinite;
  }

  .reviews-scroll-track:hover {
    animation-play-state: paused;
  }

  @keyframes scrollReviews {
    0%   { transform: translateY(0); }
    100% { transform: translateY(-50%); }
  }

  /* Review form */
  .review-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .review-form-title {
    font-size: 0.95rem;
    font-weight: 600;
    margin: 0;
  }

  .review-form input,
  .review-form textarea {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.875rem;
    box-sizing: border-box;
    font-family: inherit;
    resize: none;
    background: #fff;
    color: #1e293b;
    transition: border-color 0.2s;
  }

  .review-form input:focus,
  .review-form textarea:focus {
    outline: none;
    border-color: #2563EB;
  }

  .review-author-hint {
    font-size: 0.82rem;
    color: #64748b;
    margin: 0;
  }

  /* Star picker */
  .star-picker-wrap {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }

  .star-picker {
    display: flex;
    gap: 4px;
  }

  .star {
    font-size: 2rem;
    color: #e2e8f0;
    cursor: pointer;
    transition: color 0.15s, transform 0.1s;
    line-height: 1;
    user-select: none;
  }

  .star.active { color: #D97706; }
  .star:hover  { transform: scale(1.15); }

  .star-label-text {
    font-size: 0.78rem;
    color: #64748b;
    margin: 2px 0 0;
    min-height: 1rem;
    transition: color 0.2s;
  }

  .review-submit-btn {
    background: var(--primary, #2563EB);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 10px 16px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: opacity 0.2s, transform 0.1s;
    width: 100%;
  }

  .review-submit-btn:hover  { opacity: 0.88; }
  .review-submit-btn:active { transform: scale(0.98); }

  /* Ensure form controls never overflow */
  .review-form * { max-width: 100%; }

  @media (max-width: 479px) {
    .reviews-scroll-wrapper { height: 200px; }
  }

  @media (min-width: 1024px) {
    .reviews-scroll-wrapper { height: 230px; }
  }
</style>
@endpush


{{-- ── JS ── --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

  const stars         = document.querySelectorAll('#starPicker .star');
  const ratingInput   = document.getElementById('ratingInput');
  const starLabelText = document.getElementById('starLabelText');

  const labels = {
    1: 'Terrible',
    2: 'Poor',
    3: 'Average',
    4: 'Good',
    5: 'Excellent'
  };

  function highlightStars(val) {
    stars.forEach(s => s.classList.toggle('active', +s.dataset.value <= val));
  }

  // Hover
  stars.forEach(star => {
    star.addEventListener('mouseenter', () => {
      const val = +star.dataset.value;
      highlightStars(val);
      starLabelText.textContent = labels[val];
      starLabelText.style.color = '#D97706';
    });

    // Leave: revert to selected
    star.addEventListener('mouseleave', () => {
      const selected = +ratingInput.value;
      highlightStars(selected);
      starLabelText.textContent = selected ? labels[selected] : 'Tap a star to rate';
      starLabelText.style.color = selected ? '#D97706' : '#64748b';
    });

    // Click: lock in
    star.addEventListener('click', () => {
      const val = +star.dataset.value;
      ratingInput.value = val;
      highlightStars(val);
      starLabelText.textContent = labels[val] + ' — click again to change';
      starLabelText.style.color = '#D97706';
    });
  });

  // Block submit if no star selected
  const reviewForm = document.querySelector('.review-form');
  if (reviewForm) {
    reviewForm.addEventListener('submit', function (e) {
      if (+ratingInput.value === 0) {
        e.preventDefault();
        starLabelText.textContent = '⚠️ Please select a rating first';
        starLabelText.style.color = '#dc2626';
      }
    });
  }

  // Pause scroll animation if only 1 review (nothing to scroll)
  const track = document.getElementById('reviewsList');
  if (track && track.children.length <= 2) {
    track.style.animation = 'none';
  }

});
</script>
@endpush

   <!-- Contact Panel -->
<div class="calendar-mini">
  <div class="mini-calendar-header">
    <h3>📬 Contact Us</h3>
  </div>

  <div class="contact-list">
    <div class="c-item">
      <span class="c-icon">📧</span>
      <div class="c-details">
        <p class="c-label">Email</p>
        <p class="c-value"><a href="mailto:psalmeduofficial@gmail.com">psalmeduofficial@gmail.com</a></p>
      </div>
    </div>

    <div class="c-item">
      <span class="c-icon">📞</span>
      <div class="c-details">
        <p class="c-label">Phone</p>
        <p class="c-value"><a href="tel:+2349163490176">+2349163490176</a></p>
      </div>
    </div>

    <div class="c-item">
      <span class="c-icon">🕐</span>
      <div class="c-details">
        <p class="c-label">Support Hours</p>
        <p class="c-value">Mon – Fri, 9am – 5pm WAT</p>
      </div>
    </div>

    <!-- Social Links -->
    <div class="c-item">
      <span class="c-icon">🌐</span>
      <div class="c-details">
        <p class="c-label">Follow Us</p>
        <div class="social-links">
          <a href="https://whatsapp.com/channel/0029VaBcW9q4IBh94JXRNd0g" target="_blank" class="social-btn whatsapp">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            WhatsApp
          </a>
          {{-- <a href="https://instagram.com/psalmedu" target="_blank" class="social-btn instagram">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
            Instagram
          </a>
          <a href="https://twitter.com/psalmedu" target="_blank" class="social-btn twitter">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.748l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            Twitter / X
          </a> --}}
        </div>
      </div>
    </div>
  </div>


  <!-- Contact Form -->
  <form class="review-form contact-form" method="POST" action="{{ route('compliant') }}">
    @csrf
    <h4 class="review-form-title">✉️ Send a Message</h4>

    @guest
    <input
      type="text"
      name="sender_name"
      placeholder="Your name"
      value="{{ old('sender_name') }}"
      required
    />
    <input
      type="email"
      name="sender_email"
      placeholder="Your email"
      value="{{ old('sender_email') }}"
      required
    />
    @endguest

    @auth
    <p class="review-author-hint">
      Sending as <strong>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</strong>
    </p>
    @endauth

    <select name="topic" required>
      <option value="">Select Topic…</option>
      <option value="order"       {{ old('topic') == 'order'       ? 'selected' : '' }}>🧾 Order / Payment Issue</option>
      <option value="request"     {{ old('topic') == 'request'     ? 'selected' : '' }}>📚 Request a Course Material</option>
      <option value="general"     {{ old('topic') == 'general'     ? 'selected' : '' }}>💬 General Enquiry</option>
      <option value="submit"      {{ old('topic') == 'submit'      ? 'selected' : '' }}>📝 Submit Past Questions</option>
      <option value="partnership" {{ old('topic') == 'partnership' ? 'selected' : '' }}>🤝 Partnership / Advertising</option>
    </select>

    <textarea
      name="message"
      rows="4"
      placeholder="Your message…"
      required
    >{{ old('message') }}</textarea>

    <button type="submit" class="review-submit-btn">Send Message →</button>
  </form>

  <div class="calendar-legend">
    <div class="legend-item"><span class="legend-dot" style="background:var(--green)"></span> Online Support</div>
    <div class="legend-item"><span class="legend-dot" style="background:#2563EB"></span> Email</div>
    <div class="legend-item"><span class="legend-dot" style="background:#D97706"></span> Phone</div>
  </div>
</div>


@push('styles')
<style>
  /* Contact list */
  .contact-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 1.25rem;
  }

  .c-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 10px 12px;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
  }

  .c-icon { font-size: 1.2rem; margin-top: 2px; flex-shrink: 0; }

  .c-label {
    font-size: 0.75rem;
    color: #64748b;
    margin: 0 0 2px;
  }

  .c-value {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
    word-break: break-word;
  }

  .c-value a {
    color: #2563EB;
    text-decoration: none;
    font-weight: 600;
  }

  .c-value a:hover { text-decoration: underline; }

  /* Social buttons */
  .social-links {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 4px;
  }

  .social-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 600;
    text-decoration: none;
    transition: opacity 0.2s, transform 0.1s;
  }

  .social-btn:hover   { opacity: 0.85; transform: scale(1.03); }
  .social-btn:active  { transform: scale(0.97); }

  .social-btn.whatsapp  { background: #dcfce7; color: #15803d; }
  .social-btn.instagram { background: #fce7f3; color: #9d174d; }
  .social-btn.twitter   { background: #eff6ff; color: #1d4ed8; }

  /* Alerts */
  .contact-alert {
    padding: 10px 14px;
    border-radius: 8px;
    font-size: 0.85rem;
    margin-bottom: 12px;
  }

  .contact-alert-success {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #15803d;
  }

  .contact-alert-error {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #dc2626;
  }

  /* Contact form tweaks */
  .contact-form select {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.875rem;
    box-sizing: border-box;
    font-family: inherit;
    background: #fff;
    color: #1e293b;
    cursor: pointer;
  }

  .contact-form select:focus {
    outline: none;
    border-color: #2563EB;
  }

  .review-author-hint {
    font-size: 0.82rem;
    color: #64748b;
    margin: 0;
  }

  .review-form.contact-form * {
    max-width: 100%;
  }

  @media (max-width: 479px) {
    .social-links { gap: 8px; }
  }
</style>
@endpush

    </div>
  </div>
</section>


{{-- ── CSS (add to your stylesheet) ── --}}
@push('styles')
<style>

  /* Auto-scrolling reviews list */
.reviews-scroll-wrapper {
  overflow: hidden;
  height: 220px; /* adjust to show ~2-3 reviews */
  position: relative;
}

.reviews-scroll-track {
  display: flex;
  flex-direction: column;
  animation: scrollReviews 15s linear infinite;
}

.reviews-scroll-track:hover {
  animation-play-state: paused; /* pause on hover */
}

@keyframes scrollReviews {
  0%   { transform: translateY(0); }
  100% { transform: translateY(-50%); } /* -50% because we duplicate the items */
}
  /* Rating badge */
  .review-rating-badge {
    font-size: 0.8rem;
    color: #D97706;
    font-weight: 600;
  }

  /* Stars in review list */
  .review-stars {
    margin-left: 6px;
    font-size: 0.85rem;
  }
  .star-filled { color: #D97706; }
  .star-empty  { color: #e2e8f0; }

  /* Review & Contact shared form */
  .review-form {
    margin-top: 1rem;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .review-form-title {
    font-size: 0.95rem;
    font-weight: 600;
    margin: 0;
  }

  .review-form input,
  .review-form select,
  .review-form textarea {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.875rem;
    box-sizing: border-box;
    font-family: inherit;
    resize: none;
    background: #fff;
    color: #1e293b;
  }

  .review-form input:focus,
  .review-form textarea:focus {
    outline: none;
    border-color: #2563EB;
  }

  .review-form-row {
    display: grid;
    grid-template-columns: 1fr;
    gap: 10px;
  }

  .review-submit-btn {
    background: var(--primary, #1a6b3c);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 10px 16px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: opacity 0.2s;
    width: 100%;
  }
  .review-submit-btn:hover { opacity: 0.88; }
  .review-submit-btn:active { transform: scale(0.98); }

  /* Star picker */
  .star-picker-wrap {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }

  .star-picker {
    display: flex;
    gap: 4px;
  }

  .star {
    font-size: 2rem;
    color: #e2e8f0;
    cursor: pointer;
    transition: color 0.15s, transform 0.1s;
    line-height: 1;
    user-select: none;
  }

  .star.active  { color: #D97706; }
  .star:hover   { transform: scale(1.15); }

  .star-label-text {
    font-size: 0.78rem;
    color: #64748b;
    margin: 2px 0 0;
    min-height: 1rem;
  }

  /* Contact list items */
  .contact-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 1.25rem;
  }

  .c-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 10px 12px;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
  }

  .c-icon { font-size: 1.2rem; margin-top: 2px; }

  .c-label {
    font-size: 0.75rem;
    color: #64748b;
    margin: 0 0 2px;
  }

  .c-value {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
  }

  @media (max-width: 479px) {
    .reviews-scroll-wrapper { height: 200px; }
  }
</style>
@endpush


{{-- ── JS (add before </body> or in @push('scripts')) ── --}}
@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {

    const stars        = document.querySelectorAll('#starPicker .star');
    const ratingInput  = document.getElementById('ratingInput');
    const starLabelText = document.getElementById('starLabelText');

    const labels = {
      1: 'Terrible',
      2: 'Poor',
      3: 'Average',
      4: 'Good',
      5: 'Excellent'
    };

    // Highlight stars up to hovered index
    stars.forEach(star => {
      star.addEventListener('mouseenter', () => {
        const val = +star.dataset.value;
        stars.forEach(s => s.classList.toggle('active', +s.dataset.value <= val));
        starLabelText.textContent = labels[val];
      });

      // On leave, revert to selected value
      star.addEventListener('mouseleave', () => {
        const selected = +ratingInput.value;
        stars.forEach(s => s.classList.toggle('active', +s.dataset.value <= selected));
        starLabelText.textContent = selected ? labels[selected] : 'Tap a star to rate';
      });

      // Click locks in the rating
      star.addEventListener('click', () => {
        const val = +star.dataset.value;
        ratingInput.value = val;
        stars.forEach(s => s.classList.toggle('active', +s.dataset.value <= val));
        starLabelText.textContent = `${labels[val]} — click again to change`;
        starLabelText.style.color = '#D97706';
      });
    });

    // Block submit if no star selected
    const reviewForm = document.querySelector('.review-form');
    if (reviewForm) {
      reviewForm.addEventListener('submit', function (e) {
        if (+ratingInput.value === 0) {
          e.preventDefault();
          starLabelText.textContent = '⚠️ Please select a rating first';
          starLabelText.style.color = '#dc2626';
        }
      });
    }

  });
</script>
@endpush



<script>
function filterEvents(type) {
  document.querySelectorAll('.event-item').forEach(item => {
    item.style.display = (type === 'all' || item.dataset.type === type) ? 'flex' : 'none';
  });
}

let currentCalDate = new Date();
const eventDates = [
  @foreach($calendar as $evt)
  "{{ \Carbon\Carbon::parse($evt->start_date)->format('Y-m-d') }}",
  @endforeach
];

function renderCalendar(date) {
  const year = date.getFullYear();
  const month = date.getMonth();
  const firstDay = new Date(year, month, 1).getDay();
  const daysInMonth = new Date(year, month + 1, 0).getDate();
  const today = new Date();
  const todayIso = `${today.getFullYear()}-${String(today.getMonth()+1).padStart(2,'0')}-${String(today.getDate()).padStart(2,'0')}`;

  document.getElementById('currentMonthYear').textContent =
    date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });

  const grid = document.getElementById('calendarDays');
  grid.innerHTML = '';

  for (let i = 0; i < firstDay; i++) {
    const div = document.createElement('div');
    div.className = 'cal-day other-month';
    grid.appendChild(div);
  }

  for (let d = 1; d <= daysInMonth; d++) {
    const iso = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
    let cls = 'cal-day';
    if (iso === todayIso) cls += ' today';
    if (eventDates.includes(iso)) cls += ' has-event';
    const div = document.createElement('div');
    div.className = cls;
    div.textContent = d;
    grid.appendChild(div);
  }
}

function changeMonth(delta) {
  currentCalDate.setMonth(currentCalDate.getMonth() + delta);
  renderCalendar(currentCalDate);
}

renderCalendar(currentCalDate);
</script>

@endsection
