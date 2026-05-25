@extends('layouts.dashboard')

@push('styles')
  <link href="{{ asset('css/home-styles.css') }}" rel="stylesheet">
@endpush

@section('page-title', 'Dashboard')

@section('dashboard-content')

<div id="dashboard-root">

  {{-- ① STREAK CARD --}}
  <div class="gm-card streak-card" role="region" aria-label="Daily Streak">
    <div class="streak-eyebrow">Daily Streak</div>
    <div class="streak-number">🔥 1 <span>Day</span></div>
    <div class="streak-sub">Keep it alive — practice today</div>

    <div class="streak-badge-row">
      🏅&nbsp; Reach <strong>7 days</strong> → unlock "Consistent Scholar" badge
    </div>

    <div class="streak-bar-wrap">
      <div class="streak-bar-fill" style="width: 14.28%;"></div>
    </div>

    <div class="streak-actions">
      <button class="streak-btn streak-btn-ghost" onclick="toast('❄️ Streak freeze applied!')">
        ❄️ Freeze
      </button>
      <a href="{{ route('mock.index') }}" class="streak-btn streak-btn-solid">
        📚 Take a Mock
      </a>
    </div>
  </div>

  {{-- ② QUESTION OF THE DAY --}}
  @php $isEmailVerified = auth()->check() && !empty(auth()->user()->email_verified_at); @endphp

  @if($isEmailVerified)
    <div class="gm-card qotd-card" role="region" aria-label="Question of the Day">
      <div class="qotd-top-bar">
        <span class="pill pill-green">⚡ Question of the Day</span>
        <div class="qotd-timer">
          <i class="fas fa-clock"></i>
          <span id="qotd-countdown">04:29:17</span>
        </div>
      </div>

      <input type="hidden" id="qotd-question-id" />

      {{-- Result state --}}
      <div id="qotd-result" style="display:none;">
        <div class="qotd-result-box">
          <div class="qotd-course-tag" id="qotd-course-result"></div>
          <div class="qotd-result-title" id="qotd-result-title">—</div>
          <div class="qotd-result-msg" id="qotd-result-msg">—</div>
        </div>
        <div class="qotd-footer-row" style="margin-top:16px;">
          <div class="qotd-answered-count">👥 500 students answered today</div>
          <button class="qotd-submit-btn" disabled style="opacity:.7;">Submitted ✓</button>
        </div>
      </div>

      {{-- Question state --}}
      <div id="qotd-question-state">
        <div class="qotd-course-tag" id="qotd-course"></div>
        <div class="qotd-question" id="qotd-question-text">Loading question…</div>

        <div class="qotd-grid" id="qotd-options">
          <div class="qotd-opt" data-key="A" onclick="selectOpt(this)">
            <div class="qotd-key">A</div>
            <span class="qotd-opt-text" data-option-text="A"></span>
          </div>
          <div class="qotd-opt" data-key="B" onclick="selectOpt(this)">
            <div class="qotd-key">B</div>
            <span class="qotd-opt-text" data-option-text="B"></span>
          </div>
          <div class="qotd-opt" data-key="C" onclick="selectOpt(this)">
            <div class="qotd-key">C</div>
            <span class="qotd-opt-text" data-option-text="C"></span>
          </div>
          <div class="qotd-opt" data-key="D" onclick="selectOpt(this)">
            <div class="qotd-key">D</div>
            <span class="qotd-opt-text" data-option-text="D"></span>
          </div>
        </div>

        <div class="qotd-footer-row">
          <div class="qotd-answered-count">👥 47 students answered today</div>
          <button class="qotd-submit-btn" id="qotd-submit" onclick="submitQotd()" disabled>
            Submit &nbsp;<i class="fas fa-arrow-right" style="font-size:.75rem;"></i>
          </button>
        </div>
      </div>
    </div>

  @else
    @include('dashboard._qotd_verify_form')
  @endif

  {{-- ③ PERFORMANCE STATS --}}
  <div class="gm-card stats-card" role="region" aria-label="Your Performance">
    <div class="gm-section-header">
      <div class="gm-section-title">Your Performance</div>
    </div>

    <div class="stats-tabs" role="tablist">
      <button class="stats-tab active" onclick="switchStatTab(this,'week')">Week</button>
      <button class="stats-tab" onclick="switchStatTab(this,'month')">Month</button>
      <button class="stats-tab" onclick="switchStatTab(this,'all')">All Time</button>
    </div>

    <div class="stats-grid" id="stats-grid">
      <div class="stat-tile">
        <div class="stat-tile-label">Accuracy</div>
        <div class="stat-tile-val">54%</div>
        <div class="stat-tile-delta">↑ +12% vs last week</div>
      </div>
      <div class="stat-tile">
        <div class="stat-tile-label">Questions</div>
        <div class="stat-tile-val">47</div>
        <div class="stat-tile-delta">↑ +44 vs last week</div>
      </div>
      <div class="stat-tile">
        <div class="stat-tile-label">Study Hours</div>
        <div class="stat-tile-val">2.4h</div>
        <div class="stat-tile-delta">↑ +2.1h vs last week</div>
      </div>
      <div class="stat-tile">
        <div class="stat-tile-label">Mocks Taken</div>
        <div class="stat-tile-val">3</div>
        <div class="stat-tile-delta">↑ +2 vs last week</div>
      </div>
    </div>
  </div>

  {{-- ④ PROGRESS TO PASSING --}}
  <div class="gm-card progress-card" role="region" aria-label="Progress to Passing">
    <div class="gm-section-header">
      <div class="gm-section-title">📈 Progress to Passing</div>
    </div>

    <div class="progress-block">
      <div class="progress-meta">
        <span class="progress-label-text">Accuracy</span>
        <span class="progress-pct-text">54% → 70%</span>
      </div>
      <div class="progress-track">
        <div class="progress-fill progress-fill-green" id="acc-bar" style="width:0%;"></div>
      </div>
    </div>

    <div class="progress-block">
      <div class="progress-meta">
        <span class="progress-label-text">Mocks Completed</span>
        <span class="progress-pct-text">3 / 10</span>
      </div>
      <div class="progress-track">
        <div class="progress-fill progress-fill-gold" id="mock-bar" style="width:0%;"></div>
      </div>
    </div>

    <div class="progress-callout">
      ⚡ Take <strong>2 more mocks</strong> — students who do score <strong>18% higher</strong> on average.
    </div>
  </div>

  {{-- ⑤ LEADERBOARD --}}
  <div class="gm-card lb-card" role="region" aria-label="Top Ranks">
    <div class="gm-section-header">
      <div class="gm-section-title">Top Ranks</div>
      <a href="#" class="gm-section-action">See all →</a>
    </div>

    <div class="lb-tabs" role="tablist">
      <button class="lb-tab active" onclick="switchLbTab(this)">My Course</button>
      <button class="lb-tab" onclick="switchLbTab(this)">All Courses</button>
      <button class="lb-tab" onclick="switchLbTab(this)">This Week</button>
    </div>

    @php
      $leaders   = $performanceData['leaderboard'] ?? [];
      $lbTop     = [];
      $lbMe      = null;

      foreach ($leaders as $row) {
        if (!empty($row['you'])) { $lbMe = $row; }
        else { $lbTop[] = $row; }
      }
      $lbTop = array_values($lbTop);

      $medalEmoji = ['🥇', '🥈', '🥉'];
      $avClass    = ['av-gold', 'av-silver', 'av-bronze'];
    @endphp

    <div class="lb-list">
      @foreach(array_slice($lbTop, 0, 3) as $i => $row)
        <div class="lb-row">
          <div class="lb-rank">{{ $medalEmoji[$i] ?? '#'.($i+1) }}</div>
          <div class="lb-av {{ $avClass[$i] ?? '' }}">{{ $row['initials'] ?? '—' }}</div>
          <div>
            <div class="lb-name">{{ $row['name'] ?? '—' }}</div>
            <div class="lb-sub">Top student</div>
          </div>
          <div class="lb-pts">{{ ($row['score'] ?? 0) }} pts</div>
        </div>
      @endforeach

      <div class="lb-divider">• • •</div>

      @php
        $meRank = null;
        foreach ($leaders as $idx => $row) {
          if (!empty($row['you'])) { $meRank = $idx + 1; break; }
        }
      @endphp

      <div class="lb-row lb-me">
        <div class="lb-rank" style="font-size:.85rem; font-weight:900; color:var(--g600);">#{{ $meRank ?? '—' }}</div>
        <div class="lb-av av-me">{{ strtoupper(substr(auth()->user()->first_name,0,1).substr(auth()->user()->last_name??'',0,1)) }}</div>
        <div>
          <div class="lb-name">{{ auth()->user()->first_name }} (You)</div>
          <div class="lb-sub">{{ $lbMe ? 'Keep pushing!' : 'No data yet' }}</div>
        </div>
        <div class="lb-pts lb-pts-me">{{ $lbMe['score'] ?? 0 }} pts</div>
      </div>
    </div>
  </div>

  {{-- ⑥ BADGES --}}
  <div class="gm-card badges-card" role="region" aria-label="Your Badges">
    <div class="gm-section-header">
      <div class="gm-section-title">Your Badges</div>
      <span class="pill pill-green">2 earned · 6 locked</span>
    </div>

    <div class="badge-grid">
      <div class="badge-item earned" title="Streak Starter">
        <span class="badge-icon">🔥</span>
        <div class="badge-label">Streak Starter</div>
      </div>
      <div class="badge-item earned" title="First Mock">
        <span class="badge-icon">📝</span>
        <div class="badge-label">First Mock</div>
      </div>
      <div class="badge-item locked" title="Scholar — 5 mocks needed">
        <span class="badge-icon">🏅</span>
        <div class="badge-label">Scholar</div>
      </div>
      <div class="badge-item locked" title="Top 10">
        <span class="badge-icon">⭐</span>
        <div class="badge-label">Top 10</div>
      </div>
      <div class="badge-item locked" title="CIT Expert">
        <span class="badge-icon">🧠</span>
        <div class="badge-label">CIT Expert</div>
      </div>
      <div class="badge-item locked" title="7-Day Streak">
        <span class="badge-icon">🔥</span>
        <div class="badge-label">7-Day Streak</div>
      </div>
      <div class="badge-item locked" title="Perfect Score">
        <span class="badge-icon">🎯</span>
        <div class="badge-label">Perfect Score</div>
      </div>
      <div class="badge-item locked" title="Professor">
        <span class="badge-icon">👑</span>
        <div class="badge-label">Professor</div>
      </div>
    </div>

    <div class="badge-next-wrap">
      <div class="badge-next-icon">🏅</div>
      <div class="badge-next-info">
        <div class="badge-next-title">Next: "Scholar" Badge</div>
        <div class="badge-next-sub">3 / 5 mocks completed</div>
        <div class="badge-next-track">
          <div class="badge-next-fill" id="badge-fill" style="width:0%;"></div>
        </div>
      </div>
    </div>
  </div>

  {{-- ⑦ STUDY PARTNER --}}
  @php
    use App\Models\StudyChallenge;
    $me = auth()->user();

    $activeChallenge = StudyChallenge::query()
      ->whereIn('status', ['pending','challenger_played','completed'])
      ->where(function($q) use ($me) {
        $q->where('challenger_id', $me->id)->orWhere('opponent_id', $me->id);
      })
      ->where(function($q) use ($me) {
        $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
      })
      ->orderByDesc('created_at')
      ->first();

    $partner = null;
    if ($activeChallenge) {
      $partner = $activeChallenge->challenger_id === $me->id
        ? $activeChallenge->opponent
        : $activeChallenge->challenger;
    }
  @endphp
 @php $isEmailVerified = auth()->check() && !empty(auth()->user()->email_verified_at); @endphp

@if($isEmailVerified)
  <div class="gm-card partner-card" role="region" aria-label="Study Partner">
    <div class="gm-section-header">
      <div class="gm-section-title">Study Partner</div>
      <a href="{{ route('challenge.find-opponent') }}" class="gm-section-action"
         onclick="event.preventDefault(); document.getElementById('find-opponent-form').submit();">
        Change →
      </a>
    </div>

    <form id="find-opponent-form" method="POST" action="{{ route('challenge.find-opponent') }}" style="display:none;">
      @csrf
    </form>

    <div class="partner-vs-wrap">
      <div class="pv-player">
        <div class="pv-avatar av-me">
          {{ strtoupper(substr($me->first_name,0,1).substr($me->last_name??'',0,1)) }}
        </div>
        <div class="pv-name">{{ $me->first_name }}</div>
        <div class="pv-meta">CIT Year 2</div>
      </div>

      <div class="pv-vs-badge">VS</div>

      <div class="pv-player right">
        <div class="pv-avatar av-opp">
          {{ $partner ? strtoupper(substr($partner->first_name,0,1).substr($partner->last_name??'',0,1)) : '?' }}
        </div>
        <div class="pv-name">{{ $partner ? $partner->first_name.' '.($partner->last_name??'') : 'Find opponent' }}</div>
        <div class="pv-meta">CIT Year 2</div>
      </div>
    </div>

    @if(!$activeChallenge)
      <div class="pv-alert">
        ⚡ No active challenge — find a partner and start studying together.
      </div>
      <div class="pv-actions">
        <button class="pv-btn pv-btn-outline" onclick="document.getElementById('find-opponent-form').submit()">
          🔎 Find Opponent
        </button>
        <button class="pv-btn pv-btn-fill" onclick="document.getElementById('find-opponent-form').submit()">
          ⚡ Start Challenge
        </button>
      </div>
    @else
      <div class="pv-alert">
        ⏳ Waiting for <strong>{{ $partner?->first_name ?? 'opponent' }}</strong> to respond…
      </div>
      <div class="pv-actions">
        <button class="pv-btn pv-btn-outline" onclick="toast('⏳ Waiting for opponent')">⏳ Pending</button>
        <button class="pv-btn pv-btn-fill" onclick="toast('Challenge sent!')">⚡ Nudge</button>
      </div>
    @endif
  </div>
@endif
  {{-- ⑧ CALENDAR --}}
  <section class="calendar-section" id="calendar" aria-label="Upcoming Events">
    <div class="card">
      <div class="calendar-grid">

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
                $s       = \Carbon\Carbon::parse($event->start_date);
                $e       = \Carbon\Carbon::parse($event->end_date);
                $now     = now();
                $days    = $s->diffInDays($e) + 1;

                if ($now->between($s,$e))      { $statusText='Active';   $statusClass='status-active'; }
                elseif ($now->lt($s))          { $statusText='Upcoming'; $statusClass='status-upcoming'; }
                else                           { $statusText='Ended';    $statusClass='status-ended'; }

                $titleLower   = strtolower($event->event ?? '');
                $detectedType = 'general';
                foreach ($eventTypes as $et) {
                  if (strpos($titleLower, $et) !== false) { $detectedType = $et; break; }
                }
              @endphp

              <div class="event-item" data-type="{{ $detectedType }}" data-start="{{ $s->toDateString() }}" data-end="{{ $e->toDateString() }}">
                <div class="event-date">
                  <span class="event-day">{{ $s->format('d') }}</span>
                  <span class="event-month">{{ $s->format('M') }}</span>
                </div>
                <div class="event-details">
                  <h4>{{ $event->event ?? 'Event' }}</h4>
                  <p>
                    @if($s->toDateString() === $e->toDateString())
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

        <div class="calendar-mini">
          <div class="mini-calendar-header">
            <button class="month-nav" onclick="changeMonth(-1)">←</button>
            <h3 id="currentMonthYear"></h3>
            <button class="month-nav" onclick="changeMonth(1)">→</button>
          </div>
          <div class="mini-calendar-weekdays">
            <span>Su</span><span>Mo</span><span>Tu</span><span>We</span>
            <span>Th</span><span>Fr</span><span>Sa</span>
          </div>
          <div class="mini-calendar-days" id="calendarDays"></div>
          <div class="calendar-legend">
            <div class="legend-item">
              <span class="legend-dot" style="background:var(--g700)"></span> Today
            </div>
            <div class="legend-item">
              <span class="legend-dot" style="background:var(--g200)"></span> Event
            </div>
            <div class="legend-item">
              <span class="legend-dot" style="background:var(--gold)"></span> Upcoming
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  {{-- ⑨ REVIEWS + CONTACT --}}
  <section class="calendar-section" id="reviews" aria-label="Reviews and Contact">
    <div class="card">
      <div class="calendar-grid">

        <div class="calendar-events">
          <div class="calendar-header">
            <h3>⭐ Student Reviews</h3>
            <span class="review-rating-badge">
              {{ $reviews->count() > 0 ? number_format($reviews->avg('rating'),1) : '0.0' }} / 5
              &nbsp;({{ $reviews->count() }} {{ Str::plural('review', $reviews->count()) }})
            </span>
          </div>

          @if($reviews->count() > 0)
            <div class="reviews-scroll-wrapper">
              <div class="reviews-scroll-track" id="reviewsList">
                @foreach($reviews as $review)
                  <div class="event-item">
                    <div class="event-date">
                      <span class="event-day">{{ \Carbon\Carbon::parse($review->created_at)->format('d') }}</span>
                      <span class="event-month">{{ \Carbon\Carbon::parse($review->created_at)->format('M') }}</span>
                    </div>
                    <div class="event-details">
                      <h4>
                        {{ $review->name ?? 'Anonymous' }}
                        <span class="review-stars">
                          @for($i=1; $i<=5; $i++)
                            <span class="{{ $i<=$review->rating ? 'star-filled':'star-empty' }}">★</span>
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

          <div class="review-form-wrap">
            @php
              $myReview     = Auth::check() ? $reviews->firstWhere('user_id', Auth::id()) : null;
              $ratingLabels = ['','Terrible','Poor','Average','Good','Excellent'];
            @endphp

            <form method="POST" action="{{ route('reviews.store') }}">
              @csrf
              <div class="review-form-title">
                {{ $myReview ? '✏️ Update Your Review' : '✏️ Leave a Review' }}
              </div>

              @guest
                <input type="text" class="form-field" name="name"
                  placeholder="Your name" value="{{ old('name') }}" required />
              @endguest

              @auth
                <p class="review-author-hint">
                  Reviewing as <strong>{{ Auth::user()->first_name }}</strong>
                  @if($myReview)
                    &nbsp;·&nbsp;
                    <span style="color:var(--amber);font-size:.78rem;">submitting will update your review</span>
                  @endif
                </p>
              @endauth

              <div class="star-picker-row">
                <div class="c-label">Your Rating</div>
                <div class="star-picker" id="starPicker">
                  @for($i=1; $i<=5; $i++)
                    <span class="star-pick {{ $myReview && $myReview->rating>=$i ? 'active':'' }}"
                          data-value="{{ $i }}">★</span>
                  @endfor
                </div>
                <input type="hidden" name="rating" id="ratingInput" value="{{ $myReview->rating ?? old('rating',0) }}" />
                <p class="star-label-text" id="starLabelText">
                  {{ $myReview ? $ratingLabels[$myReview->rating] : 'Tap a star to rate' }}
                </p>
              </div>

              <textarea class="form-field" name="message" rows="3"
                placeholder="Share your experience…" required>{{ $myReview->message ?? old('message') }}</textarea>

              <button type="submit" class="form-submit-btn">
                {{ $myReview ? 'Update Review →' : 'Submit Review →' }}
              </button>
            </form>
          </div>
        </div>

        <div class="calendar-mini">
          <div class="mini-calendar-header" style="justify-content:flex-start; gap:10px; margin-bottom:20px;">
            <h3>📬 Contact Us</h3>
          </div>

          <div class="contact-list">
            <div class="c-item">
              <div class="c-icon">📧</div>
              <div class="c-details">
                <p class="c-label">Email</p>
                <p class="c-value"><a href="mailto:psalmeduofficial@gmail.com">psalmeduofficial@gmail.com</a></p>
              </div>
            </div>
            <div class="c-item">
              <div class="c-icon">📞</div>
              <div class="c-details">
                <p class="c-label">Phone</p>
                <p class="c-value"><a href="tel:+2349163490176">+234 916 349 0176</a></p>
              </div>
            </div>
            <div class="c-item">
              <div class="c-icon">🕐</div>
              <div class="c-details">
                <p class="c-label">Support Hours</p>
                <p class="c-value">Mon – Fri, 9am – 5pm WAT</p>
              </div>
            </div>
          </div>

          <form method="POST" action="{{ route('compliant') }}">
            @csrf
            <div class="review-form-title">✉️ Send a Message</div>

            @guest
              <input type="text" class="form-field" name="sender_name"
                placeholder="Your name" value="{{ old('sender_name') }}" required />
              <input type="email" class="form-field" name="sender_email"
                placeholder="Your email" value="{{ old('sender_email') }}" required />
            @endguest

            @auth
              <p class="review-author-hint">
                Sending as <strong>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</strong>
              </p>
            @endauth

            <select class="form-field" name="topic" required>
              <option value="">Select topic…</option>
              <option value="order"       {{ old('topic')=='order'       ?'selected':'' }}>🧾 Order / Payment Issue</option>
              <option value="request"     {{ old('topic')=='request'     ?'selected':'' }}>📚 Request Course Material</option>
              <option value="general"     {{ old('topic')=='general'     ?'selected':'' }}>💬 General Enquiry</option>
              <option value="submit"      {{ old('topic')=='submit'      ?'selected':'' }}>📝 Submit Past Questions</option>
              <option value="partnership" {{ old('topic')=='partnership' ?'selected':'' }}>🤝 Partnership / Advertising</option>
            </select>

            <textarea class="form-field" name="message" rows="4"
              placeholder="Your message…" required>{{ old('message') }}</textarea>

            <button type="submit" class="form-submit-btn">Send Message →</button>
          </form>
        </div>

      </div>
    </div>
  </section>

</div>

{{-- Toast container --}}
<div class="toast-container" id="toastContainer"></div>

@push('scripts')

{{-- ── GLOBAL TOAST ── --}}
<script>
function toast(msg, duration = 3000) {
  const container = document.getElementById('toastContainer');
  if (!container) return;
  const el = document.createElement('div');
  el.className = 'toast-msg';
  el.textContent = msg;
  container.appendChild(el);
  setTimeout(() => {
    el.style.transition = 'opacity .3s, transform .3s';
    el.style.opacity = '0';
    el.style.transform = 'translateY(8px)';
    setTimeout(() => el.remove(), 320);
  }, duration);
}
</script>

@if($isEmailVerified ?? false)
<script>
/* ── COUNTDOWN ── */
(function () {
  let secs = 4 * 3600 + 29 * 60 + 17;
  const el = document.getElementById('qotd-countdown');
  if (!el) return;
  const tick = () => {
    if (secs <= 0) { el.textContent = '00:00:00'; return; }
    const h = String(Math.floor(secs / 3600)).padStart(2, '0');
    const m = String(Math.floor((secs % 3600) / 60)).padStart(2, '0');
    const s = String(secs % 60).padStart(2, '0');
    el.textContent = `${h}:${m}:${s}`;
    secs--;
  };
  tick();
  setInterval(tick, 1000);
})();

/* ── QOTD ── */
let qotdAnswered = false;

function selectOpt(el) {
  if (qotdAnswered) return;
  document.querySelectorAll('.qotd-opt').forEach(o => o.classList.remove('selected'));
  el.classList.add('selected');
  const btn = document.getElementById('qotd-submit');
  if (btn) btn.disabled = false;
}

function loadQotd() {
  // Ensure option text is visible even if server returns null/empty
  fetch('{{ route('qotd.current') }}', { headers: { Accept: 'application/json' } })

    .then(r => { if (!r.ok) throw new Error('Failed'); return r.json(); })
    .then(data => {
      document.getElementById('qotd-question-state').style.display = 'block';
      document.getElementById('qotd-result').style.display = 'none';
      document.getElementById('qotd-question-id').value = data.question_id || '';
      qotdAnswered = false;

      const courseEl = document.getElementById('qotd-course');
      const questionEl = document.getElementById('qotd-question-text');
      if (courseEl) courseEl.textContent = data.course_label || `Course #${data.course_id || ''}`;
      if (questionEl) questionEl.textContent = data.question || '';

      ['A', 'B', 'C', 'D'].forEach(key => {
        const opt  = document.querySelector(`.qotd-opt[data-key="${key}"]`);
        const span = document.querySelector(`[data-option-text="${key}"]`);
        if (span) span.textContent = data.options?.[key] || '';
        if (opt) {
          opt.classList.remove('selected', 'correct', 'wrong');
          opt.onclick = function () { selectOpt(this); };
        }
      });

      const btn = document.getElementById('qotd-submit');
      if (btn) { btn.disabled = true; btn.textContent = 'Submit '; }
    })
    .catch(() => {
      const el = document.getElementById('qotd-question-text');
      if (el) el.textContent = 'Question of the Day is unavailable right now.';
    });
}

function submitQotd() {
  if (qotdAnswered) return;
  const btn = document.getElementById('qotd-submit');
  if (btn) btn.disabled = true;

  qotdAnswered = true;

  const btn      = document.getElementById('qotd-submit');
  const qId      = document.getElementById('qotd-question-id')?.value;
  const selEl    = document.querySelector('.qotd-opt.selected');
  const selOpt   = selEl ? selEl.dataset.key.toUpperCase().trim() : null;

  if (!qId || !selOpt) {
    toast('⚠️ Please select an option first');
    qotdAnswered = false;
    return;
  }
  if (btn) { btn.disabled = true; btn.textContent = 'Submitting…'; }

  fetch('{{ route('qotd.submit') }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
    },
    body: JSON.stringify({ question_id: qId, selected_option: selOpt })
  })
    .then(r => r.json())
            .then(data => {
              const selOptEl  = document.querySelector(`.qotd-opt[data-key="${data.selected_option}"]`);
              const corrOptEl = document.querySelector(`.qotd-opt[data-key="${data.correct_option}"]`);
              if (selOptEl)  { selOptEl.classList.remove('selected'); selOptEl.classList.add(data.is_correct ? 'correct' : 'wrong'); }
              if (corrOptEl)   corrOptEl.classList.add('correct');

              const resultTitle = document.getElementById('qotd-result-title');
              const resultMsg   = document.getElementById('qotd-result-msg');
              const courseRes   = document.getElementById('qotd-course-result');



      if (courseRes)   courseRes.textContent = document.getElementById('qotd-course')?.textContent || '';
      if (resultTitle) {
        resultTitle.textContent = data.is_correct ? '✅ Correct!' : '❌ Not quite';
        resultTitle.style.color = data.is_correct ? '#16A34A' : '#DC2626';
      }
      if (resultMsg)
        resultMsg.textContent = data.is_correct
          ? `You earned +${data.points} points. Great work!`
          : `Correct answer was ${data.correct_option}. Keep studying!`;

      setTimeout(() => {
        document.getElementById('qotd-question-state').style.display = 'none';
        document.getElementById('qotd-result').style.display = 'block';
      }, 700);

      toast(data.is_correct ? `✅ Correct! +${data.points} pts` : '❌ Not quite — keep going!');
    })
    .catch(() => {
      toast('❌ Failed to submit. Try again.');
      qotdAnswered = false;
      if (btn) { btn.disabled = false; btn.textContent = 'Submit'; }
    });
}

/* ── STAT TABS ── */
window.switchStatTab = function (btn, key) {
  document.querySelectorAll('.stats-tab').forEach(t => t.classList.remove('active'));
  btn.classList.add('active');

  const meta = {
    week:  ['54%', '↑ +12% vs last week', '47',  '↑ +44 vs last week',  '2.4h', '↑ +2.1h vs last week', '3',  '↑ +2 vs last week'],
    month: ['48%', '↑ +6% vs last month', '189', '↑ +120 vs last month', '9.2h','↑ +4.5h vs last month','11', '↑ +4 vs last month'],
    all:   ['52%', 'Overall average',      '340', 'Total answered',       '18.6h','Total study time',    '19', 'All mocks taken'],
  };
  const d     = meta[key] || meta.week;
  const tiles = document.querySelectorAll('.stat-tile');
  const vals   = [d[0], d[2], d[4], d[6]];
  const deltas = [d[1], d[3], d[5], d[7]];

  tiles.forEach((tile, i) => {
    const v = tile.querySelector('.stat-tile-val');
    const dt = tile.querySelector('.stat-tile-delta');
    if (v)  v.textContent  = vals[i]   || '—';
    if (dt) dt.textContent = deltas[i] || '';
  });
};

/* ── LB TABS ── */
window.switchLbTab = function (btn) {
  document.querySelectorAll('.lb-tab').forEach(t => t.classList.remove('active'));
  btn.classList.add('active');
};

/* ── EVENT FILTER ── */
window.filterEvents = function (type) {
  document.querySelectorAll('.event-item').forEach(item => {
    item.style.display = (type === 'all' || item.dataset.type === type) ? '' : 'none';
  });
};

/* ── CALENDAR MINI ── */
window.changeMonth = (delta) => {
  if (typeof window.__calState === 'undefined') return;
  window.__calState.month += delta;
  window.renderMiniCalendar();
};

window.renderMiniCalendar = () => {
  const root = document.getElementById('calendarDays');
  const title = document.getElementById('currentMonthYear');
  if (!root || !title || !window.__calState) return;

  const state = window.__calState;
  const y = state.year;
  const m = state.month; // 0-11

  const first = new Date(y, m, 1);
  const last = new Date(y, m + 1, 0);
  const daysInMonth = last.getDate();

  const today = new Date();
  const todayKey = today.toISOString().slice(0,10); // YYYY-MM-DD

  // events from server
  const items = Array.from(document.querySelectorAll('#eventsList .event-item'));
  const eventDaySet = new Set();
  items.forEach(it => {
    const s = it.dataset.start;
    const e = it.dataset.end;
    if (!s) return;
    const start = new Date(s + 'T00:00:00');
    const end = e ? new Date(e + 'T23:59:59') : start;
    // mark each day in range (small range expected)
    for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
      eventDaySet.add(d.toISOString().slice(0,10));
    }
  });

  const monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];
  title.textContent = `${monthNames[m] || ''} ${y}`;

  root.innerHTML = '';

  // weekday: JS Sunday=0
  const startWeekday = first.getDay();

  // leading blanks
  for (let i = 0; i < startWeekday; i++) {
    const blank = document.createElement('div');
    blank.className = 'calendar-mini-day blank';
    root.appendChild(blank);
  }

  // days
  for (let day = 1; day <= daysInMonth; day++) {
    const key = new Date(y, m, day).toISOString().slice(0,10);
    const cell = document.createElement('div');
    cell.className = 'calendar-mini-day';
    cell.textContent = day;

    if (key === todayKey) cell.style.background = 'var(--g700)';
    if (eventDaySet.has(key)) {
      cell.style.border = '1.5px solid var(--g200)';
      cell.style.color = 'var(--ink)';
      // if it's also today, keep background; otherwise set a subtle color
      if (key !== todayKey) cell.style.background = 'rgba(0,0,0,0.02)';
    }

    root.appendChild(cell);
  }
};

document.addEventListener('DOMContentLoaded', () => {
  // init state
  if (typeof window.__calState === 'undefined') {
    const now = new Date();
    window.__calState = { month: now.getMonth(), year: now.getFullYear() };

    // normalize in case of overflow
    const norm = () => {
      let { month, year } = window.__calState;
      while (month < 0) { month += 12; year -= 1; }
      while (month > 11) { month -= 12; year += 1; }
      window.__calState.month = month;
      window.__calState.year = year;
    };
    window.__calState.normalize = norm;
  }

  if (window.__calState.normalize) window.__calState.normalize();
  window.renderMiniCalendar();
});

/* ── ANIMATE BARS ON LOAD ── */
document.addEventListener('DOMContentLoaded', () => {
  loadQotd();

  setTimeout(() => {
    const acc   = document.getElementById('acc-bar');
    const mock  = document.getElementById('mock-bar');
    const badge = document.getElementById('badge-fill');
    if (acc)   acc.style.width   = '77%';
    if (mock)  mock.style.width  = '30%';
    if (badge) badge.style.width = '60%';
  }, 400);
});
</script>
@endif

{{-- ── STAR PICKER ── --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
  const stars      = document.querySelectorAll('#starPicker .star-pick');
  const ratingInput = document.getElementById('ratingInput');
  const labelText  = document.getElementById('starLabelText');
  if (!stars.length || !ratingInput || !labelText) return;

  const labels = { 1: 'Terrible', 2: 'Poor', 3: 'Average', 4: 'Good', 5: 'Excellent' };

  function paintStars(val) {
    stars.forEach(s => s.classList.toggle('active', +s.dataset.value <= val));
  }

  stars.forEach(star => {
    star.addEventListener('mouseenter', () => {
      paintStars(+star.dataset.value);
      labelText.textContent = labels[+star.dataset.value] || '';
      labelText.style.color = 'var(--gold)';
    });
    star.addEventListener('mouseleave', () => {
      const sel = +ratingInput.value;
      paintStars(sel);
      labelText.textContent = sel ? labels[sel] : 'Tap a star to rate';
      labelText.style.color = sel ? 'var(--gold)' : 'var(--ink-muted)';
    });
    star.addEventListener('click', () => {
      const val = +star.dataset.value;
      ratingInput.value = val;
      paintStars(val);
      labelText.textContent = labels[val] + ' — click to change';
      labelText.style.color = 'var(--gold)';
    });
  });

  const reviewForm = document.querySelector('#reviews form');
  if (reviewForm) {
    reviewForm.addEventListener('submit', e => {
      if (+ratingInput.value === 0) {
        e.preventDefault();
        labelText.textContent = '⚠️ Please select a rating';
        labelText.style.color = 'var(--red)';
      }
    });
  }
});
</script>

@endpush

@endsection