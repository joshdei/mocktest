@extends('layouts.dashboard')

@section('title')
@section('page-title')

@php
use Illuminate\Support\Str;
@endphp

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
  --red:         #DC2626;
  --red-light:   #FEF2F2;
  --shadow:      rgba(26,107,60,.08);
  --shadow-md:   rgba(26,107,60,.14);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.result-wrapper {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background:
    radial-gradient(ellipse 60% 60% at 50% 40%, rgba(26,107,60,.07) 0%, transparent 60%),
    var(--off-white);
  padding: 24px;
  position: relative;
}

.result-wrapper::before {
  content: '';
  position: fixed;
  inset: 0;
  z-index: 0;
  background-image:
    linear-gradient(rgba(26,107,60,.04) 1px, transparent 1px),
    linear-gradient(90deg, rgba(26,107,60,.04) 1px, transparent 1px);
  background-size: 48px 48px;
  pointer-events: none;
}

.result-card {
  background: var(--white);
  border: 1.5px solid var(--border);
  border-radius: 24px;
  padding: 46px 50px;
  max-width: 680px;
  width: 100%;
  position: relative;
  z-index: 1;
  box-shadow: 0 20px 60px rgba(26,107,60,.1);
  animation: fadeUp .5s ease;
}
@keyframes fadeUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:none} }

.score-circle {
  width: 130px;
  height: 130px;
  border-radius: 50%;
  border: 6px solid var(--green);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  margin: 0 auto 28px;
  background: var(--green-pale);
  box-shadow: 0 0 0 10px rgba(26,107,60,.07);
}
.score-circle.pass { border-color: var(--green); }
.score-circle.fail { border-color: var(--red); background: var(--red-light); }

.score-num {
  font-family: 'Playfair Display', serif;
  font-size: 2.4rem;
  font-weight: 800;
  color: var(--green);
  line-height: 1;
}
.score-circle.fail .score-num { color: var(--red); }

.score-label {
  font-size: .72rem;
  font-weight: 700;
  color: var(--text-muted);
  text-transform: uppercase;
  letter-spacing: .08em;
  margin-top: 4px;
}

.result-title {
  font-family: 'Playfair Display', serif;
  font-size: 1.5rem;
  text-align: center;
  color: var(--text);
  margin-bottom: 6px;
}

.result-subtitle {
  text-align: center;
  font-size: .93rem;
  color: var(--text-muted);
  margin-bottom: 28px;
}

.result-stats {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 12px;
  margin-bottom: 28px;
}

.rs-item {
  background: var(--gray-50);
  border: 1.5px solid var(--border);
  border-radius: 12px;
  padding: 16px;
  text-align: center;
}

.rs-num {
  font-family: 'Playfair Display', serif;
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--text);
}
.rs-item .rs-num.correct { color: var(--green); }
.rs-item .rs-num.wrong { color: var(--red); }
.rs-item .rs-num.marks { color: var(--green); }

.rs-label {
  font-size: .72rem;
  color: var(--text-muted);
  margin-top: 3px;
}

.result-stats-secondary {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 12px;
  margin-bottom: 28px;
}

.result-btns {
  display: flex;
  gap: 12px;
  margin-bottom: 32px;
}

.btn-review {
  flex: 1;
  background: var(--green-light);
  color: var(--green);
  border: 1.5px solid rgba(26,107,60,.25);
  border-radius: 11px;
  padding: 13px;
  font-family: 'DM Sans', sans-serif;
  font-size: .92rem;
  font-weight: 700;
  cursor: pointer;
  transition: all .2s;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}
.btn-review:hover { background: var(--green); color: #fff; }

.btn-retake {
  flex: 1;
  background: var(--green);
  color: #fff;
  border: none;
  border-radius: 11px;
  padding: 13px;
  font-family: 'DM Sans', sans-serif;
  font-size: .92rem;
  font-weight: 700;
  cursor: pointer;
  transition: all .2s;
  box-shadow: 0 4px 14px var(--shadow-md);
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}
.btn-retake:hover { background: var(--green-mid); }

/* ── BREAKDOWN ── */
.breakdown-section {
  border-top: 1.5px solid var(--border);
  padding-top: 24px;
}

.breakdown-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 16px;
}

.breakdown-title {
  font-family: 'Playfair Display', serif;
  font-size: 1rem;
  font-weight: 700;
  color: var(--text);
}

.breakdown-count {
  font-size: .85rem;
  color: var(--text-muted);
}

.breakdown-item {
  display: flex;
  align-items: flex-start;
  gap: 14px;
  padding: 16px;
  border: 1.5px solid var(--border);
  border-radius: 12px;
  margin-bottom: 10px;
  background: var(--white);
  transition: all .18s;
}
.breakdown-item:hover { border-color: var(--green); }
.breakdown-item.correct { border-left: 4px solid var(--green); }
.breakdown-item.wrong { border-left: 4px solid var(--red); }

.breakdown-icon {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  display: grid;
  place-items: center;
  flex-shrink: 0;
  font-size: .9rem;
  font-weight: 700;
}
.breakdown-item.correct .breakdown-icon {
  background: var(--green-light);
  color: var(--green);
}
.breakdown-item.wrong .breakdown-icon {
  background: var(--red-light);
  color: var(--red);
}

.breakdown-content { flex: 1; min-width: 0; }

.breakdown-question-num {
  font-size: .7rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .08em;
  color: var(--gray-400);
  margin-bottom: 4px;
}

.breakdown-question {
  font-size: .9rem;
  font-weight: 600;
  color: var(--text);
  margin-bottom: 8px;
  line-height: 1.5;
}

.breakdown-answer {
  font-size: .8rem;
  color: var(--text-muted);
  line-height: 1.5;
}

.breakdown-answer strong {
  font-weight: 700;
}
.breakdown-item.correct .breakdown-answer strong { color: var(--green); }
.breakdown-item.wrong .breakdown-answer strong { color: var(--red); }

/* ── PAGINATION ── */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 8px;
  margin-top: 24px;
}

.pagination-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  min-width: 40px;
  height: 40px;
  padding: 0 12px;
  border-radius: 8px;
  border: 1.5px solid var(--border);
  background: var(--white);
  color: var(--gray-600);
  font-size: .85rem;
  font-weight: 600;
  cursor: pointer;
  transition: all .18s;
  text-decoration: none;
}
.pagination-btn:hover:not(:disabled) {
  border-color: var(--green);
  color: var(--green);
  background: var(--green-pale);
}
.pagination-btn.active {
  background: var(--green);
  border-color: var(--green);
  color: #fff;
}
.pagination-btn:disabled {
  opacity: 0.4;
  cursor: not-allowed;
}
.pagination-ellipsis {
  color: var(--gray-400);
  font-size: .85rem;
}

/* ── DASHBOARD LINK ── */
.dashboard-link {
  display: block;
  text-align: center;
  margin-top: 20px;
  color: var(--text-muted);
  text-decoration: none;
  font-size: .85rem;
  transition: color .2s;
}
.dashboard-link:hover { color: var(--green); }

@media(max-width: 600px) {
  .result-card { padding: 32px 24px; }
  .result-stats { grid-template-columns: 1fr; }
  .result-stats-secondary { grid-template-columns: 1fr; }
  .result-btns { flex-direction: column; }
  .score-circle { width: 100px; height: 100px; }
  .score-num { font-size: 1.8rem; }
}
</style>

<div class="result-wrapper">
  <div class="result-card">
  
    <div class="score-circle {{ $passed ? 'pass' : 'fail' }}">
      <div class="score-num">{{ $percentage }}%</div>
      <div class="score-label">Score</div>
    </div>

    <h2 class="result-title">
      {{ $statusIcon }} {{ $statusText }} — Grade {{ $grade }}
    </h2>
    <p class="result-subtitle">
      {{ $statusMessage }}
    </p>

    <div class="result-stats">
      <div class="rs-item">
        <div class="rs-num correct">{{ $correctAnswers }}</div>
        <div class="rs-label">Correct</div>
      </div>
      <div class="rs-item">
        <div class="rs-num wrong">{{ $wrongAnswers }}</div>
        <div class="rs-label">Wrong</div>
      </div>
      <div class="rs-item">
        <div class="rs-num">{{ $totalQuestions }}</div>
        <div class="rs-label">Total</div>
      </div>
    </div>

    <div class="result-stats-secondary">
      <div class="rs-item">
        <div class="rs-num marks">{{ $correctAnswers * 4 }}/{{ $totalQuestions * 4 }}</div>
        <div class="rs-label">Marks</div>
      </div>
      <div class="rs-item">
        <div class="rs-num">{{ $percentage }}%</div>
        <div class="rs-label">Percentage</div>
      </div>
      <div class="rs-item">
        <div class="rs-num">{{ $timeUsed ?? '—' }}</div>
        <div class="rs-label">Time Used</div>
      </div>
    </div>

    <div class="result-btns">
      <a href="{{ route('mock.review') }}" class="btn-review">📋 Review Answers</a>
      <a href="{{ route('mock.index') }}" class="btn-retake">🔄 Retake Exam</a>
    </div>

   

    <a href="{{ route('dashboard') }}" class="dashboard-link">← Back to Dashboard</a>
  </div>
</div>
@endsection