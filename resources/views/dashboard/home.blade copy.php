@extends('layouts.dashboard')

@section('page-title', 'Dashboard')

@section('dashboard-content')

<style>
/* ── GAMIFICATION DASHBOARD ── */
.gm-grid {
  display: grid;
  margin: 0 auto;
  grid-template-columns: 1fr 1fr 1fr;
  gap: 18px;
  max-width: 1200px;
}
@media(max-width:1100px){ .gm-grid { grid-template-columns: 1fr 1fr; } }
@media(max-width:680px){  .gm-grid { grid-template-columns: 1fr; } }

/* ── CARD BASE ── */
.gm-card {
  background: var(--white);
  border: 1.5px solid var(--border);
  border-radius: 16px;
  overflow: hidden;
  transition: box-shadow .2s, transform .2s;
}
.gm-card:hover { box-shadow: 0 6px 28px var(--shadow-md); transform: translateY(-2px); }

/* ── STREAK CARD ── */
.streak-card {
  background: linear-gradient(135deg, var(--green) 0%, #0f4d2a 100%);
  border: none;
  color: #fff;
  padding: 24px;
  position: relative;
  overflow: hidden;
}
.streak-card::before {
  content: '';
  position: absolute;
  top: -30px; right: -30px;
  width: 130px; height: 130px;
  border-radius: 50%;
  background: rgba(255,255,255,.06);
}
.streak-card::after {
  content: '';
  position: absolute;
  bottom: -20px; left: 40px;
  width: 80px; height: 80px;
  border-radius: 50%;
  background: rgba(255,255,255,.04);
}
.streak-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 14px; }
.streak-label { font-size: .72rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; opacity: .75; margin-bottom: 4px; }
.streak-title { font-size: 1.15rem; font-weight: 800; }
.streak-sub { font-size: .78rem; opacity: .72; margin-top: 3px; }
.streak-fire { font-size: 2.6rem; line-height: 1; }
.streak-count-row { display: flex; align-items: baseline; gap: 6px; margin: 16px 0 6px; }
.streak-num { font-family: 'Playfair Display', serif; font-size: 3rem; font-weight: 900; line-height: 1; }
.streak-unit { font-size: .85rem; font-weight: 600; opacity: .8; }
.streak-badge-hint {
  background: rgba(255,255,255,.12);
  border: 1px solid rgba(255,255,255,.2);
  border-radius: 8px;
  padding: 8px 12px;
  font-size: .78rem;
  margin: 14px 0;
  display: flex; align-items: center; gap: 8px;
}
.streak-actions { display: flex; gap: 10px; margin-top: 16px; }
.streak-btn {
  flex: 1;
  padding: 10px 12px;
  border-radius: 10px;
  font-size: .8rem; font-weight: 700;
  cursor: pointer; border: none;
  font-family: 'DM Sans', sans-serif;
  transition: all .18s;
  text-align: center; text-decoration: none; display: block;
}
.streak-btn-ghost {
  background: rgba(255,255,255,.15);
  color: #fff;
  border: 1.5px solid rgba(255,255,255,.3);
}
.streak-btn-ghost:hover { background: rgba(255,255,255,.25); }
.streak-btn-solid {
  background: #fff;
  color: var(--green);
}
.streak-btn-solid:hover { background: #f0f7f2; }

/* ── QUESTION OF THE DAY ── */
.qotd-card { padding: 22px; }
.qotd-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
.qotd-badge {
  display: inline-flex; align-items: center; gap: 6px;
  background: #FFF8E7; color: #B45309;
  border: 1px solid #FDE68A;
  border-radius: 20px;
  font-size: .72rem; font-weight: 700;
  padding: 4px 10px;
  letter-spacing: .04em;
}
.qotd-timer {
  display: flex; align-items: center; gap: 6px;
  font-size: .78rem; font-weight: 700; color: var(--gray-500);
}
.qotd-timer .ti { color: #EF4444; }
.qotd-course { font-size: .72rem; font-weight: 700; color: var(--green); letter-spacing: .06em; text-transform: uppercase; margin-bottom: 8px; }
.qotd-q { font-size: .9rem; font-weight: 600; color: var(--text); line-height: 1.5; margin-bottom: 16px; }
.qotd-options { display: flex; flex-direction: column; gap: 8px; }
.qotd-opt {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 14px;
  border: 1.5px solid var(--border);
  border-radius: 10px;
  cursor: pointer;
  transition: all .18s;
  font-size: .85rem; color: var(--gray-700);
  font-family: 'DM Sans', sans-serif;
}
.qotd-opt:hover { border-color: var(--green); background: var(--green-pale); color: var(--green); }
.qotd-opt.selected { border-color: var(--green); background: var(--green-light); color: var(--green); font-weight: 700; }
.qotd-opt.correct  { border-color: #16A34A; background: #F0FDF4; color: #15803D; font-weight: 700; }
.qotd-opt.wrong    { border-color: #DC2626; background: #FEF2F2; color: #DC2626; }
.qotd-opt-key {
  width: 24px; height: 24px; border-radius: 6px;
  background: var(--gray-100); color: var(--gray-500);
  font-size: .72rem; font-weight: 800;
  display: grid; place-items: center; flex-shrink: 0;
  border: 1px solid var(--border);
}
.qotd-opt.selected .qotd-opt-key,
.qotd-opt.correct .qotd-opt-key  { background: var(--green); color: #fff; border-color: var(--green); }
.qotd-opt.wrong .qotd-opt-key    { background: #DC2626; color: #fff; border-color: #DC2626; }
.qotd-footer { display: flex; align-items: center; justify-content: space-between; margin-top: 14px; }
.qotd-count { font-size: .75rem; color: var(--gray-400); }
.qotd-submit {
  background: var(--green); color: #fff;
  border: none; border-radius: 10px;
  padding: 10px 20px;
  font-size: .82rem; font-weight: 700;
  cursor: pointer; font-family: 'DM Sans', sans-serif;
  display: flex; align-items: center; gap: 6px;
  transition: all .18s;
}
.qotd-submit:hover { background: #155c32; }
.qotd-submit:disabled { opacity: .55; cursor: not-allowed; }

/* ── PERFORMANCE STATS ── */
.stats-card { padding: 22px; }
.stats-tabs { display: flex; gap: 4px; background: var(--gray-100); border-radius: 10px; padding: 4px; margin-bottom: 18px; }
.stats-tab {
  flex: 1; text-align: center;
  padding: 7px 8px;
  border-radius: 8px;
  font-size: .78rem; font-weight: 600;
  color: var(--gray-500); cursor: pointer;
  transition: all .18s; border: none; background: none;
  font-family: 'DM Sans', sans-serif;
}
.stats-tab.active { background: var(--white); color: var(--green); box-shadow: 0 1px 4px rgba(0,0,0,.08); }
.stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 18px; }
.stat-tile {
  background: var(--gray-50);
  border: 1.5px solid var(--border);
  border-radius: 12px;
  padding: 14px;
}
.stat-tile-label { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--gray-400); margin-bottom: 6px; }
.stat-tile-val { font-family: 'Playfair Display', serif; font-size: 1.6rem; font-weight: 800; color: var(--text); line-height: 1; margin-bottom: 4px; }
.stat-tile-delta { font-size: .72rem; font-weight: 700; color: #16A34A; }
.stat-tile-delta.down { color: #DC2626; }

/* ── PROGRESS CARD ── */
.progress-card { padding: 22px; }
.progress-section { margin-bottom: 16px; }
.progress-label { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
.progress-label span:first-child { font-size: .82rem; font-weight: 600; color: var(--text); }
.progress-label .progress-pct { font-size: .82rem; font-weight: 700; color: var(--green); }
.progress-track {
  height: 8px; background: var(--border);
  border-radius: 100px; overflow: hidden;
}
.progress-fill {
  height: 100%; background: var(--green);
  border-radius: 100px;
  transition: width .8s cubic-bezier(.4,0,.2,1);
}
.progress-fill.amber { background: #D97706; }
.progress-hint {
  margin-top: 12px;
  background: #FFFBEB;
  border: 1px solid #FDE68A;
  border-radius: 10px;
  padding: 10px 14px;
  font-size: .8rem;
  color: #92400E;
  display: flex; align-items: center; gap: 8px;
}

/* ── LEADERBOARD ── */
.lb-card { padding: 22px; }
.lb-tabs { display: flex; gap: 4px; background: var(--gray-100); border-radius: 10px; padding: 4px; margin-bottom: 16px; }
.lb-tab {
  flex: 1; text-align: center;
  padding: 6px 8px;
  border-radius: 8px;
  font-size: .75rem; font-weight: 600;
  color: var(--gray-500); cursor: pointer;
  transition: all .18s; border: none; background: none;
  font-family: 'DM Sans', sans-serif;
}
.lb-tab.active { background: var(--white); color: var(--green); box-shadow: 0 1px 4px rgba(0,0,0,.08); }
.lb-list { display: flex; flex-direction: column; gap: 6px; }
.lb-row {
  display: flex; align-items: center; gap: 12px;
  padding: 10px 12px; border-radius: 10px;
  transition: background .15s;
}
.lb-row:hover { background: var(--gray-50); }
.lb-row.me {
  background: var(--green-light);
  border: 1.5px solid rgba(26,107,60,.2);
  border-radius: 10px;
}
.lb-rank { font-size: .75rem; font-weight: 800; color: var(--gray-400); width: 22px; text-align: center; flex-shrink: 0; }
.lb-rank.gold   { color: #D97706; font-size: .9rem; }
.lb-rank.silver { color: #9CA3AF; font-size: .9rem; }
.lb-rank.bronze { color: #B45309; font-size: .9rem; }
.lb-av {
  width: 32px; height: 32px; border-radius: 50%;
  background: var(--green); color: #fff;
  display: grid; place-items: center;
  font-size: .72rem; font-weight: 800;
  flex-shrink: 0;
}
.lb-av.gold   { background: #FEF3C7; color: #92400E; }
.lb-av.silver { background: var(--gray-100); color: var(--gray-700); }
.lb-av.bronze { background: #FDF4E7; color: #78350F; }
.lb-av.me     { background: var(--green); box-shadow: 0 0 0 2px #fff, 0 0 0 3.5px var(--green); }
.lb-name { font-size: .85rem; font-weight: 600; color: var(--text); flex: 1; }
.lb-pts { font-size: .82rem; font-weight: 800; color: var(--text); }
.lb-dots { text-align: center; color: var(--gray-300); font-size: .8rem; padding: 4px 0; letter-spacing: 2px; }
.lb-gap-hint { font-size: .73rem; color: #D97706; font-weight: 700; margin-top: 4px; }

/* ── BADGES ── */
.badges-card { padding: 22px; }
.badge-count { font-size: .75rem; color: var(--gray-400); font-weight: 600; margin-bottom: 14px; }
.badge-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
.badge-item { display: flex; flex-direction: column; align-items: center; gap: 5px; cursor: pointer; }
.badge-icon {
  width: 52px; height: 52px; border-radius: 14px;
  display: grid; place-items: center;
  font-size: 1.5rem;
  position: relative;
  transition: transform .18s;
}
.badge-icon:hover { transform: scale(1.08); }
.badge-item.earned .badge-icon { background: var(--green-light); border: 2px solid rgba(26,107,60,.25); }
.badge-item.locked .badge-icon { background: var(--gray-100); border: 2px solid var(--border); filter: grayscale(1); opacity: .45; }
.badge-label { font-size: .65rem; font-weight: 600; color: var(--gray-500); text-align: center; line-height: 1.2; }
.badge-item.earned .badge-label { color: var(--green); }
.badge-next {
  margin-top: 14px;
  background: var(--green-pale);
  border-radius: 10px;
  padding: 10px 14px;
  display: flex; align-items: center; gap: 10px;
}
.badge-next-label { font-size: .8rem; font-weight: 700; color: var(--text); }
.badge-next-sub { font-size: .72rem; color: var(--text-muted); }
.badge-next-progress { margin-top: 6px; height: 5px; background: var(--border); border-radius: 100px; overflow: hidden; }
.badge-next-fill { height: 100%; background: var(--green); border-radius: 100px; }

/* ── STUDY PARTNER ── */
.partner-card { padding: 22px; }
.partner-vs {
  display: flex; align-items: center; gap: 10px;
  margin: 14px 0;
}
.pv-player {
  flex: 1; background: var(--gray-50); border: 1.5px solid var(--border);
  border-radius: 12px; padding: 12px; text-align: center;
}
.pv-player.me { background: var(--green-light); border-color: rgba(26,107,60,.25); }
.pv-av {
  width: 40px; height: 40px; border-radius: 50%;
  background: var(--green); color: #fff;
  display: grid; place-items: center;
  font-size: .78rem; font-weight: 800;
  margin: 0 auto 8px;
}
.pv-av.them { background: #6B7280; }
.pv-name { font-size: .78rem; font-weight: 700; color: var(--text); margin-bottom: 2px; }
.pv-meta { font-size: .68rem; color: var(--text-muted); }
.pv-sep { font-family: 'Playfair Display', serif; font-size: 1.1rem; font-weight: 900; color: var(--gray-300); }
.pv-stats { display: flex; flex-direction: column; gap: 8px; margin-bottom: 14px; }
.pv-stat-row { display: flex; align-items: center; gap: 0; border-radius: 8px; overflow: hidden; }
.pv-stat-label { font-size: .72rem; font-weight: 700; color: var(--gray-500); text-transform: uppercase; letter-spacing: .06em; margin-bottom: 5px; }
.pv-bar-wrap { display: flex; gap: 3px; align-items: center; }
.pv-bar-me  { height: 8px; background: var(--green);  border-radius: 4px 0 0 4px; transition: width .7s; }
.pv-bar-sep { width: 3px; flex-shrink: 0; }
.pv-bar-them { height: 8px; background: var(--gray-300); border-radius: 0 4px 4px 0; transition: width .7s; }
.pv-val-me   { font-size: .78rem; font-weight: 800; color: var(--green); }
.pv-val-them { font-size: .78rem; font-weight: 800; color: var(--gray-500); }
.pv-alert {
  background: #FFF8E7;
  border: 1px solid #FDE68A;
  border-radius: 10px;
  padding: 10px 14px;
  font-size: .8rem; color: #92400E;
  display: flex; align-items: flex-start; gap: 8px;
  margin-bottom: 14px;
}
.pv-actions { display: flex; gap: 10px; }
.pv-btn {
  flex: 1; padding: 10px 12px;
  border-radius: 10px; font-size: .8rem; font-weight: 700;
  cursor: pointer; border: none;
  font-family: 'DM Sans', sans-serif;
  transition: all .18s; text-align: center; text-decoration: none;
  display: block;
}
.pv-btn-outline {
  background: transparent;
  border: 1.5px solid var(--border);
  color: var(--gray-700);
}
.pv-btn-outline:hover { border-color: var(--green); color: var(--green); }
.pv-btn-fill { background: var(--green); color: #fff; }
.pv-btn-fill:hover { background: #155c32; }

/* ── SECTION HEADER ── */
.gm-section-header {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 14px;
}
.gm-section-title { font-family: 'Playfair Display', serif; font-size: 1rem; font-weight: 800; color: var(--text); }
.gm-section-action { font-size: .78rem; font-weight: 700; color: var(--green); cursor: pointer; text-decoration: none; }

/* ── TOAST OVERRIDE ── */
.toast { z-index: 9999; }

/* ── SPAN 2 COL ── */
.col-span-2 { grid-column: span 2; }
@media(max-width:680px){ .col-span-2 { grid-column: span 1; } }
</style>

<div class="gm-grid">

  <!-- ① STREAK CARD -->
  <div class="streak-card gm-card">
    <div class="streak-top">
      <div>
        <div class="streak-label">Daily Streak</div>
        <div class="streak-title">🔥 1 Day Streak</div>
        <div class="streak-sub">Keep it alive today</div>
      </div>
      <div class="streak-fire">🔥</div>
    </div>
    <div class="streak-count-row">
      <div class="streak-num">1</div>
      <div class="streak-unit">Day</div>
    </div>
    <div class="streak-badge-hint">
      <span>🏅</span>
      <span>Reach <strong>7 days</strong> → unlock "Consistent Scholar" badge</span>
    </div>
    <div style="height:6px;background:rgba(255,255,255,.15);border-radius:100px;overflow:hidden;margin-bottom:16px;">
      <div style="height:100%;width:14.28%;background:#fff;border-radius:100px;transition:width 1s;"></div>
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

  @php
    $isEmailVerified = auth()->check() && !empty(auth()->user()->email_verified_at);
  @endphp

  <!-- ② QUESTION OF THE DAY -->
  @if($isEmailVerified)
  <div class="qotd-card gm-card col-span-2">
    <div class="qotd-header">
      <span class="qotd-badge">⚡ Question of the Day</span>
      <div class="qotd-timer">
        <i class="fas fa-clock" style="color:#EF4444;font-size:.8rem;"></i>
        <span id="qotd-countdown">04:29:17</span>
      </div>
    </div>

    <input type="hidden" id="qotd-question-id" />

    <!-- RESULT STATE (hidden until submit) -->
  <div id="qotd-result" style="display:none;">
      <div class="qotd-course" id="qotd-course-result"></div>
      <div class="qotd-q" id="qotd-result-title" style="margin-bottom:10px;">
        —
      </div>
      <div class="progress-hint" id="qotd-result-msg" style="margin-top:0;">
        —
      </div>
      <div class="qotd-footer" style="margin-top:16px;">
        <div class="qotd-count">👥 47 students answered</div>
        <button class="qotd-submit" id="qotd-result-submit" disabled style="opacity:0.7;">
          Submitted ✓
        </button>
      </div>
    </div>

    <!-- QUESTION STATE -->
    <div id="qotd-question-state">
      <div class="qotd-course" id="qotd-course"></div>
      <div class="qotd-q" id="qotd-question-text"></div>
      <div class="qotd-options" id="qotd-options">
        <div class="qotd-opt" data-key="A" onclick="selectOpt(this)">
          <div class="qotd-opt-key">A</div>
          <span data-option-text="A"></span>
        </div>
        <div class="qotd-opt" data-key="B" onclick="selectOpt(this)">
          <div class="qotd-opt-key">B</div>
          <span data-option-text="B"></span>
        </div>
        <div class="qotd-opt" data-key="C" onclick="selectOpt(this)">
          <div class="qotd-opt-key">C</div>
          <span data-option-text="C"></span>
        </div>
        <div class="qotd-opt" data-key="D" onclick="selectOpt(this)">
          <div class="qotd-opt-key">D</div>
          <span data-option-text="D"></span>
        </div>
      </div>

      <div class="qotd-footer">
        <div class="qotd-count">👥 47 students answered</div>
        <button class="qotd-submit" id="qotd-submit" onclick="submitQotd()" disabled>
          Submit <i class="fas fa-arrow-right" style="font-size:.75rem;"></i>
        </button>
      </div>
    </div>
  </div>
  @else
    <div class="qotd-card gm-card col-span-2">
      <div class="qotd-header">
        <span class="qotd-badge">⚡ Question of the Day</span>
      </div>
      <div class="qotd-q">Verify your email to see the Question of the Day.</div>
    </div>
  @endif

  <!-- ③ PERFORMANCE STATS -->
  <div class="stats-card gm-card">
    <div class="gm-section-header">
      <div class="gm-section-title">Your Performance</div>
    </div>
    <div class="stats-tabs">
      <button class="stats-tab active" onclick="switchStatTab(this,'week')">Week</button>
      <button class="stats-tab" onclick="switchStatTab(this,'month')">Month</button>
      <button class="stats-tab" onclick="switchStatTab(this,'all')">All</button>
    </div>
    <div class="stats-grid" id="stats-grid">
      <div class="stat-tile">
        <div class="stat-tile-label">Accuracy</div>
        <div class="stat-tile-val">54%</div>
        <div class="stat-tile-delta">↑ +12% vs last</div>
      </div>
      <div class="stat-tile">
        <div class="stat-tile-label">Questions</div>
        <div class="stat-tile-val">47</div>
        <div class="stat-tile-delta">↑ +44 vs last</div>
      </div>
      <div class="stat-tile">
        <div class="stat-tile-label">Study Hours</div>
        <div class="stat-tile-val">2.4h</div>
        <div class="stat-tile-delta">↑ +2.1h vs last</div>
      </div>
      <div class="stat-tile">
        <div class="stat-tile-label">Mocks Taken</div>
        <div class="stat-tile-val">3</div>
        <div class="stat-tile-delta">↑ +2 vs last</div>
      </div>
    </div>
  </div>

  <!-- ④ PROGRESS TO PASSING -->
  <div class="progress-card gm-card">
    <div class="gm-section-header">
      <div class="gm-section-title">📈 Progress to Passing</div>
    </div>
    <div class="progress-section">
      <div class="progress-label">
        <span>Accuracy</span>
        <span class="progress-pct">54% → 70%</span>
      </div>
      <div class="progress-track">
        <div class="progress-fill" id="acc-bar" style="width:0%"></div>
      </div>
    </div>
    <div class="progress-section">
      <div class="progress-label">
        <span>Mocks Completed</span>
        <span class="progress-pct">3 / 10</span>
      </div>
      <div class="progress-track">
        <div class="progress-fill amber" id="mock-bar" style="width:0%"></div>
      </div>
    </div>
    <div class="progress-hint">
      ⚡ Take <strong>2 more mocks</strong> — students who do score <strong>18% higher</strong>
    </div>
  </div>

  <!-- ⑤ LEADERBOARD -->
  <div class="lb-card gm-card">
    <div class="gm-section-header">
      <div class="gm-section-title">Top Ranks</div>
      <a href="#" class="gm-section-action">See all →</a>
    </div>
    <div class="lb-tabs">
      <button class="lb-tab active" onclick="switchLbTab(this)">My Course</button>
      <button class="lb-tab" onclick="switchLbTab(this)">All Courses</button>
      <button class="lb-tab" onclick="switchLbTab(this)">This Week</button>
    </div>
    <div class="lb-list">
      @php
        $leaders = $performanceData['leaderboard'] ?? [];
        $top = [];
        $me = null;

        foreach ($leaders as $row) {
          if (!empty($row['you'])) {
            $me = $row;
          } else {
            $top[] = $row;
          }
        }

        $top = array_values($top);
      @endphp

      @if(count($top) > 0)
        <div class="lb-row">
          <div class="lb-rank gold">🥇</div>
          <div class="lb-av gold">{{ $top[0]['initials'] ?? '—' }}</div>
          <div class="lb-name">{{ $top[0]['name'] ?? '—' }}</div>
          <div class="lb-pts">{{ isset($top[0]['score']) ? $top[0]['score'].' pts' : '0 pts' }}</div>
        </div>
      @endif

      @if(count($top) > 1)
        <div class="lb-row">
          <div class="lb-rank silver">🥈</div>
          <div class="lb-av silver">{{ $top[1]['initials'] ?? '—' }}</div>
          <div class="lb-name">{{ $top[1]['name'] ?? '—' }}</div>
          <div class="lb-pts">{{ isset($top[1]['score']) ? $top[1]['score'].' pts' : '0 pts' }}</div>
        </div>
      @endif

      @if(count($top) > 2)
        <div class="lb-row">
          <div class="lb-rank bronze">🥉</div>
          <div class="lb-av bronze">{{ $top[2]['initials'] ?? '—' }}</div>
          <div class="lb-name">{{ $top[2]['name'] ?? '—' }}</div>
          <div class="lb-pts">{{ isset($top[2]['score']) ? $top[2]['score'].' pts' : '0 pts' }}</div>
        </div>
      @endif

      <div class="lb-dots">• • •</div>

      <div class="lb-row me">
        @php
          $meRank = null;
          if (is_array($leaders) && count($leaders) > 0) {
            foreach ($leaders as $idx => $row) {
              if (!empty($row['you'])) {
                $meRank = $idx + 1;
                break;
              }
            }
          }
        @endphp

        <div class="lb-rank" style="color:var(--green);font-weight:900;">#{{ $meRank ?? '—' }}</div>
        <div class="lb-av me">{{ strtoupper(substr(auth()->user()->first_name, 0, 1) . substr(auth()->user()->last_name, 0, 1)) }}</div>
        <div>
          <div class="lb-name">You ({{ auth()->user()->first_name }})</div>
          <div class="lb-gap-hint">
            ⚡ {{ $me ? 'Keep pushing!' : 'No leaderboard data yet' }}
          </div>
        </div>
        <div class="lb-pts" style="color:var(--green);">{{ isset($me['score']) ? $me['score'].' pts' : '0 pts' }}</div>
      </div>
    </div>
  </div>

  <!-- ⑥ BADGES -->
  <div class="badges-card gm-card">
    <div class="gm-section-header">
      <div class="gm-section-title">Your Badges</div>
    </div>
    <div class="badge-count">2 earned · 6 locked</div>
    <div class="badge-grid">
      <div class="badge-item earned" title="Streak Starter">
        <div class="badge-icon">🔥</div>
        <div class="badge-label">Streak Starter</div>
      </div>
      <div class="badge-item earned" title="First Mock">
        <div class="badge-icon">📝</div>
        <div class="badge-label">First Mock</div>
      </div>
      <div class="badge-item locked" title="Scholar — 5 mocks needed">
        <div class="badge-icon">🏅</div>
        <div class="badge-label">Scholar</div>
      </div>
      <div class="badge-item locked" title="Top 10">
        <div class="badge-icon">⭐</div>
        <div class="badge-label">Top 10</div>
      </div>
      <div class="badge-item locked" title="CIT Expert">
        <div class="badge-icon">🧠</div>
        <div class="badge-label">CIT Expert</div>
      </div>
      <div class="badge-item locked" title="7-Day Streak">
        <div class="badge-icon">🔥</div>
        <div class="badge-label">7-Day Streak</div>
      </div>
      <div class="badge-item locked" title="Perfect Score">
        <div class="badge-icon">🎯</div>
        <div class="badge-label">Perfect Score</div>
      </div>
      <div class="badge-item locked" title="Professor">
        <div class="badge-icon">👑</div>
        <div class="badge-label">Professor</div>
      </div>
    </div>
    <div class="badge-next">
      <div style="font-size:1.4rem;flex-shrink:0;">🏅</div>
      <div style="flex:1;">
        <div class="badge-next-label">Next: "Scholar" Badge</div>
        <div class="badge-next-sub">3 / 5 mocks completed</div>
        <div class="badge-next-progress" style="margin-top:6px;">
          <div class="badge-next-fill" id="badge-fill" style="width:0%;"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- ⑦ STUDY PARTNER -->
  @php
    use App\Models\StudyChallenge;

    $me = auth()->user();

    $activeChallenge = StudyChallenge::query()
      ->whereIn('status', ['pending', 'challenger_played', 'completed'])
      ->where(function($q) use ($me) {
        $q->where('challenger_id', $me->id)
          ->orWhere('opponent_id', $me->id);
      })
      ->where(function($q) use ($me) {
        $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
      })
      ->orderByDesc('created_at')
      ->first();

    $partner = null;
    $myRole = null;
    $partnerScore = null;
    $myScore = null;
    $inviteOpponent = null;

    if ($activeChallenge) {
      if ($activeChallenge->challenger_id === $me->id) {
        $myRole = 'challenger';
        $partner = $activeChallenge->opponent;
      } else {
        $myRole = 'opponent';
        $partner = $activeChallenge->challenger;
      }
      if ($activeChallenge->status === 'challenger_played' || $activeChallenge->status === 'completed') {
        $myScore = $myRole === 'challenger' ? $activeChallenge->challenger_score : $activeChallenge->opponent_score;
        $partnerScore = $myRole === 'challenger' ? $activeChallenge->opponent_score : $activeChallenge->challenger_score;
      }
    }

  @endphp

  <div class="partner-card gm-card col-span-2">
    <div class="gm-section-header">
      <div class="gm-section-title">Study Partner</div>
      <a href="{{ route('challenge.find-opponent') }}" class="gm-section-action" onclick="event.preventDefault();document.getElementById('find-opponent-form').submit();">Change →</a>
    </div>

    <form id="find-opponent-form" method="POST" action="{{ route('challenge.find-opponent') }}" style="display:none;">
      @csrf
    </form>

    <div class="partner-vs">
      <div class="pv-player me">
        <div class="pv-av">{{ strtoupper(substr($me->first_name, 0, 1) . substr($me->last_name ?? '', 0, 1)) }}</div>
        <div class="pv-name">You ({{ $me->first_name }})</div>
        <div class="pv-meta">CIT Year 2</div>
      </div>
      <div class="pv-sep">VS</div>
      <div class="pv-player {{ $partner ? '' : 'them' }}">
        <div class="pv-av them">{{ $partner ? strtoupper(substr($partner->first_name, 0, 1) . substr($partner->last_name ?? '', 0, 1)) : '—' }}</div>
        <div class="pv-name">{{ $partner ? $partner->first_name.' '.($partner->last_name ?? '') : 'Find an opponent' }}</div>
        <div class="pv-meta">CIT Year 2</div>
      </div>
    </div>

    @if(!$activeChallenge)
      <div class="pv-alert">
        <span>⚡ Find a partner and start a study challenge.</span>
      </div>
      <div class="pv-actions">
        <button class="pv-btn pv-btn-outline" type="button" onclick="document.getElementById('find-opponent-form').submit()">
          🔎 Find Opponent
        </button>
        <button class="pv-btn pv-btn-fill" type="button" onclick="document.getElementById('find-opponent-form').submit()">
          ⚡ Start
        </button>
      </div>
    @else
      @if($activeChallenge->status === 'pending' && $myRole === 'challenger')
        <div class="pv-alert">
          <span>⚡ Preparing your challenge — send when ready.</span>
        </div>
        <div class="pv-actions">
          <button class="pv-btn pv-btn-outline" type="button" onclick="toast('📊 Stats view coming soon')">📊 View Stats</button>
          <form method="POST" action="{{ route('challenge.send') }}" style="flex:1;">
            @csrf
            <input type="hidden" name="challenge" value="{{ $activeChallenge->id }}">
            <button class="pv-btn pv-btn-fill" type="submit">⚡ Send Challenge</button>
          </form>
        </div>
      @elseif($activeChallenge->status === 'challenger_played' && $myRole === 'opponent')
        @php
          $challengerScore = $activeChallenge->challenger_score;
        @endphp
        <div class="pv-alert">
          <span>⚡ {{ $partner->first_name }} challenged you — They scored {{ $challengerScore }}%. Beat it!</span>
        </div>
        <div class="pv-actions">
          <button class="pv-btn pv-btn-outline" type="button" onclick="toast('📊 View Stats coming soon')">📊 View Stats</button>
          <a href="{{ route('challenge.play', ['challenge' => $activeChallenge->id, 'role' => 'opponent']) }}" class="pv-btn pv-btn-fill">✅ Accept &amp; Play</a>
        </div>
      @elseif($activeChallenge->status === 'completed')
        @php
          $winnerId = $activeChallenge->winner_id;
          $isDraw = $winnerId === null;
          $isWin = !$isDraw && $winnerId === $me->id;
          $isLose = !$isDraw && $winnerId !== $me->id;
        @endphp
        <div class="pv-alert">
          <span>
            @if($isDraw)
              🤝 It’s a Draw!
            @elseif($isWin)
              🏆 You Won!
            @else
              😢 You Lost
            @endif
          </span>
        </div>
        <div class="pv-actions">
          <button class="pv-btn pv-btn-outline" type="button" onclick="toast('📊 Stats view coming soon')">📊 View Stats</button>
          <form method="POST" action="{{ route('challenge.send') }}" style="flex:1;">
            @csrf
            <input type="hidden" name="challenge" value="{{ $activeChallenge->id }}">
            <button class="pv-btn pv-btn-fill" type="button" onclick="window.location='{{ route('challenge.play', ['challenge'=>$activeChallenge->id,'role'=>'challenger']) }}'" style="display:none;">Rematch</button>
            <a href="{{ route('challenge.play', ['challenge' => $activeChallenge->id, 'role' => 'challenger']) }}" class="pv-btn pv-btn-fill" onclick="event.preventDefault();toast('Rematch flow not fully wired yet');">⚡ Rematch</a>
          </form>
        </div>
      @else
        <div class="pv-alert">
          <span>Waiting…</span>
        </div>
        <div class="pv-actions">
          <button class="pv-btn pv-btn-outline" type="button" onclick="toast('⏳ Waiting for opponent')">⏳ Waiting</button>
          <button class="pv-btn pv-btn-fill" type="button" onclick="toast('Waiting…')">⚡</button>
        </div>
      @endif
    @endif
  </div>


</div><!-- /gm-grid -->

@push('scripts')
@if($isEmailVerified)
<script>
// scripts enabled only for verified users

// ── COUNTDOWN TIMER ──
(function() {
  let secs = 4 * 3600 + 29 * 60 + 17;
  const el = document.getElementById('qotd-countdown');
  if (!el) return;
  const tick = () => {
    if (secs <= 0) { el.textContent = '00:00:00'; return; }
    const h = String(Math.floor(secs / 3600)).padStart(2,'0');
    const m = String(Math.floor((secs % 3600) / 60)).padStart(2,'0');
    const s = String(secs % 60).padStart(2,'0');
    el.textContent = `${h}:${m}:${s}`;
    secs--;
  };
  tick(); setInterval(tick, 1000);
})();

// ── QUESTION OF THE DAY ──
let qotdAnswered = false;
function selectOpt(el) {
  if (qotdAnswered) return;
  document.querySelectorAll('.qotd-opt').forEach(o => o.classList.remove('selected'));
  el.classList.add('selected');
  document.getElementById('qotd-submit').disabled = false;
}

function setQotdLoading(isLoading) {
  const btn = document.getElementById('qotd-submit');
  if (!btn) return;

  btn.disabled = true;
  btn.textContent = isLoading ? 'Loading...' : 'Submit';
}

function loadQotd() {
  setQotdLoading(true);

  fetch('{{ route('qotd.current') }}', {
    headers: {
      'Accept': 'application/json'
    }
  })
    .then(r => {
      if (!r.ok) throw new Error('Unable to load question');
      return r.json();
    })
    .then(data => {
      const questionState = document.getElementById('qotd-question-state');
      const resultState = document.getElementById('qotd-result');
      const questionId = document.getElementById('qotd-question-id');
      const course = document.getElementById('qotd-course');
      const courseResult = document.getElementById('qotd-course-result');
      const questionText = document.getElementById('qotd-question-text');

      qotdAnswered = false;
      if (questionState) questionState.style.display = 'block';
      if (resultState) resultState.style.display = 'none';
      if (questionId) questionId.value = data.question_id || '';
      // QotdController::current returns course_id (no course_label)
      if (course) course.textContent = (data.course_label ?? null) || `Course #${data.course_id || ''}`;
      if (courseResult) courseResult.textContent = (data.course_label ?? null) || `Course #${data.course_id || ''}`;

      if (questionText) questionText.textContent = data.question || '';


      ['A', 'B', 'C', 'D'].forEach(key => {
        const opt = document.querySelector(`.qotd-opt[data-key="${key}"]`);
        const text = document.querySelector(`[data-option-text="${key}"]`);

        if (text) text.textContent = data.options?.[key] || '';
        if (opt) {
          opt.classList.remove('selected', 'correct', 'wrong');
          opt.onclick = function() { selectOpt(this); };
          opt.style.cursor = 'pointer';
        }
      });

      setQotdLoading(false);
    })
    .catch(err => {
      console.error(err);
      const questionText = document.getElementById('qotd-question-text');
      if (questionText) questionText.textContent = 'Question of the Day is unavailable right now.';
      toast('Could not load Question of the Day');
    });
}

function submitQotd() {
  if (qotdAnswered) return;
  qotdAnswered = true;

  const btn = document.getElementById('qotd-submit');
  btn.disabled = true;
  btn.textContent = 'Submitting…';

  const questionId = document.getElementById('qotd-question-id')?.value;
  const selectedEl = document.querySelector('.qotd-opt.selected');
  const selectedOption = selectedEl ? selectedEl.dataset.key : null;

  if (!questionId || !selectedOption) {
    toast('Please select an option');
    btn.disabled = false;
    btn.textContent = 'Submit';
    qotdAnswered = false;
    return;
  }

  // Normalize selected option to A-D (backend expects A|B|C|D)
  const selectedOptionNormalized = String(selectedOption).toUpperCase().trim();


  // Disable UI

  fetch('{{ route('qotd.submit') }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    },
    body: JSON.stringify({
      question_id: questionId,
      selected_option: selectedOptionNormalized
    })
  })
    .then(r => r.json())
    .then(data => {
      if (!data) throw new Error('Empty response');

      const questionState = document.getElementById('qotd-question-state');
      const resultState = document.getElementById('qotd-result');

      const correctOptEl = document.querySelector(`.qotd-opt[data-key="${data.correct_option}"]`);
      const selectedOptEl = document.querySelector(`.qotd-opt[data-key="${data.selected_option}"]`);

      if (selectedOptEl) {
        if (data.is_correct) {
          selectedOptEl.classList.remove('selected');
          selectedOptEl.classList.add('correct');
        } else {
          selectedOptEl.classList.remove('selected');
          selectedOptEl.classList.add('wrong');
        }
      }

      if (correctOptEl) {
        correctOptEl.classList.add('correct');
      }

      const resultTitle = document.getElementById('qotd-result-title');
      const resultMsg = document.getElementById('qotd-result-msg');

      if (resultTitle) {
        resultTitle.textContent = data.is_correct ? '✅ Correct!' : '❌ Not quite';
        resultTitle.style.color = data.is_correct ? '#16A34A' : '#DC2626';
      }

      if (resultMsg) {
        resultMsg.textContent = data.is_correct
          ? `You earned +${data.points} points.`
          : `Correct answer is ${data.correct_option}.`;
      }

      if (questionState) questionState.style.display = 'none';
      if (resultState) resultState.style.display = 'block';

      btn.textContent = data.is_correct ? 'Answered ✓' : 'Answered ✓';

      toast(data.is_correct ? `✅ Correct! +${data.points} pts earned` : '❌ Not quite');
    })
    .catch(err => {
      console.error(err);
      toast('Failed to submit. Try again');
      qotdAnswered = false;
      btn.disabled = false;
      btn.textContent = 'Submit';
    });
}



// ── STAT TABS ──
document.addEventListener('DOMContentLoaded', loadQotd);

const statData = {
  week:  { acc:'54%', '+12% vs last', q:'47',  qd:'+44 vs last', h:'2.4h', hd:'+2.1h vs last', m:'3',  md:'+2 vs last' },
  month: { acc:'48%', qd:'+12 vs last', q:'189', h:'9.2h', hd:'+4.5h vs last', m:'11', md:'+4 vs last' },
  all:   { acc:'52%', q:'340', h:'18.6h', m:'19' }
};
const statMeta = {
  week:  ['54%','+12% vs last','47','+44 vs last','2.4h','+2.1h vs last','3','+2 vs last'],
  month: ['48%','+6% vs prev','189','+120 vs prev','9.2h','+4.5h vs prev','11','+4 vs prev'],
  all:   ['52%','Overall avg','340','Total questions','18.6h','Total study','19','All mocks'],
};
function switchStatTab(btn, key) {
  document.querySelectorAll('.stats-tab').forEach(t => t.classList.remove('active'));
  btn.classList.add('active');
  const d = statMeta[key] || statMeta.week;
  const tiles = document.querySelectorAll('.stat-tile');
  const vals = [d[0], d[2], d[4], d[6]];
  const deltas = [d[1], d[3], d[5], d[7]];
  tiles.forEach((t,i) => {
    t.querySelector('.stat-tile-val').textContent  = vals[i]   || '—';
    t.querySelector('.stat-tile-delta').textContent = deltas[i] || '';
  });
}

// ── LEADERBOARD TABS ──
function switchLbTab(btn) {
  document.querySelectorAll('.lb-tab').forEach(t => t.classList.remove('active'));
  btn.classList.add('active');
}

// ── ANIMATE PROGRESS BARS ON LOAD ──
window.addEventListener('DOMContentLoaded', function() {
  setTimeout(() => {
    const acc = document.getElementById('acc-bar');
    const mock = document.getElementById('mock-bar');
    const badge = document.getElementById('badge-fill');
    if (acc)   acc.style.width   = '77%';   // 54/70 = 77%
    if (mock)  mock.style.width  = '30%';   // 3/10
    if (badge) badge.style.width = '60%';   // 3/5
  }, 300);
});
</script>
@endpush
@endif

@endsection
