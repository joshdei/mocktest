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
  max-width: 620px;
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
  display: flex;
  align-items: center;
  gap: 11px;
  margin-bottom: 32px;
}
.logo-mark {
  width: 38px; height: 38px;
  background: var(--green);
  border-radius: 10px;
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

/* Info grid */
.info-grid {
  display: grid; grid-template-columns: 1fr 1fr;
  gap: 12px; margin-bottom: 28px;
}
.info-item {
  background: var(--gray-50); border: 1.5px solid var(--border);
  border-radius: 12px; padding: 14px 16px;
}
.ii-label {
  font-size: .68rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: .08em; color: var(--gray-400); margin-bottom: 5px;
}
.ii-val  { font-size: 1rem; font-weight: 700; color: var(--text); }
.ii-sub  { font-size: .73rem; color: var(--text-muted); margin-top: 2px; }

/* Rules */
.rules-box {
  background: #FEF3C7; border: 1.5px solid rgba(217,119,6,.25);
  border-radius: 12px; padding: 16px 18px; margin-bottom: 28px;
}
.rules-box h4 {
  font-size: .8rem; font-weight: 700; color: var(--amber);
  margin-bottom: 10px; display: flex; align-items: center; gap: 6px;
}
.rules-box ul { list-style: none; display: flex; flex-direction: column; gap: 7px; }
.rules-box ul li {
  font-size: .82rem; color: var(--gray-700);
  display: flex; align-items: flex-start; gap: 8px;
}
.rules-box ul li::before { content: '→'; color: var(--amber); font-weight: 700; flex-shrink: 0; }

/* Duration selector */
.setup-label {
  display: block;
  font-size: .78rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: .08em; color: var(--gray-500);
  margin-bottom: 12px;
}
.duration-options {
  display: flex; gap: 10px; margin-bottom: 28px;
}
.duration-option { flex: 1; }
.duration-option input[type="radio"] { display: none; }
.duration-option label {
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  padding: 16px 10px;
  border: 2px solid var(--border); border-radius: 14px;
  background: var(--white); cursor: pointer;
  transition: all .2s;
}
.duration-option label:hover {
  border-color: var(--green); background: var(--green-pale);
}
.duration-option input:checked + label {
  border-color: var(--green); background: var(--green-light);
  box-shadow: 0 0 0 3px rgba(26,107,60,.12);
}
.dur-time {
  font-family: 'Playfair Display', serif;
  font-size: 1.6rem; font-weight: 800; color: var(--text);
  line-height: 1;
}
.duration-option input:checked + label .dur-time { color: var(--green); }
.dur-label {
  font-size: .7rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: .08em; color: var(--gray-400); margin-top: 3px;
}
.duration-option input:checked + label .dur-label { color: var(--green-mid); }

/* Submit button */
.btn-start {
  width: 100%; background: var(--green); color: #fff;
  border: none; border-radius: 13px; padding: 16px;
  font-family: 'DM Sans', sans-serif; font-size: 1rem; font-weight: 700;
  cursor: pointer; transition: all .2s;
  box-shadow: 0 6px 20px var(--shadow-md); letter-spacing: .01em;
  margin-bottom: 18px;
}
.btn-start:hover {
  background: var(--green-mid); transform: translateY(-2px);
  box-shadow: 0 10px 28px var(--shadow-md);
}
.btn-start:active { transform: none; }

.setup-note {
  text-align: center; font-size: .78rem; color: var(--text-muted);
  margin-bottom: 12px;
}
.setup-back {
  display: block; text-align: center;
  font-size: .82rem; font-weight: 600; color: var(--green);
  text-decoration: none; transition: opacity .2s;
}
.setup-back:hover { opacity: .7; }

@media (max-width: 640px) {
  .setup-card { padding: 32px 24px; }
  .info-grid  { grid-template-columns: 1fr; }
  .setup-title { font-size: 1.5rem; }
}
</style>

<div class="setup-wrapper">
  <div class="setup-card">


    <h1 class="setup-title">{{ $course->course_name }}</h1>
    <p class="course-tag">Course: <strong>{{ $course->course_code }}</strong></p>

    <div class="info-grid">
      <div class="info-item">
        <div class="ii-label">Questions</div>
        <div class="ii-val">{{ $questions->count() }} Questions</div>
        <div class="ii-sub">Multiple choice (MCQ)</div>
      </div>
      <div class="info-item">
        <div class="ii-label">Duration</div>
        <div class="ii-val">Choose below</div>
        <div class="ii-sub">Auto-submits on timeout</div>
      </div>
      <div class="info-item">
        <div class="ii-label">Marks</div>
        <div class="ii-val">{{ $questions->count() * 4 }} Marks</div>
        <div class="ii-sub">4 marks per question</div>
      </div>
      <div class="info-item">
        <div class="ii-label">Pass Mark</div>
        <div class="ii-val">40%</div>
        <div class="ii-sub">Grade B threshold</div>
      </div>
    </div>

    <div class="rules-box">
      <h4>⚠️ Instructions — Read Carefully</h4>
      <ul>
        <li>Answer ALL {{ $questions->count() }} questions before submitting.</li>
        <li>Each question carries 4 marks. No negative marking.</li>
        <li>You may flag questions for review and come back to them.</li>
        <li>The timer starts immediately when you click "Start Exam".</li>
        <li>Results and explanations are shown after submission.</li>
      </ul>
    </div>

<form method="POST" action="{{ route('mock.start', $course->id) }}">
  @csrf

  {{-- ✅ Pass plan_id forward --}}
  <input type="hidden" name="plan_id" value="{{ $plan->id ?? session('exam_plan_id') }}">

  <label class="setup-label">Select Duration</label>

  <div class="duration-options">
    <div class="duration-option">
      <input type="radio" name="duration" id="dur15" value="15">
      <label for="dur15">
        <span class="dur-time">15</span>
        <span class="dur-label">min</span>
      </label>
    </div>
    <div class="duration-option">
      <input type="radio" name="duration" id="dur30" value="30">
      <label for="dur30">
        <span class="dur-time">30</span>
        <span class="dur-label">min</span>
      </label>
    </div>
    <div class="duration-option">
      <input type="radio" name="duration" id="dur60" value="60" checked>
      <label for="dur60">
        <span class="dur-time">60</span>
        <span class="dur-label">min</span>
      </label>
    </div>
  </div>

  <button type="submit" class="btn-start">📝 Start Exam Now →</button>
</form>
    <a href="{{ route('mock.index') }}" class="setup-back">← Back to Courses</a>

  </div>
</div>
@endsection