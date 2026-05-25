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
  --red:         #DC2626;
  --red-light:   #FEF2F2;
  --shadow:      rgba(26,107,60,.08);
  --shadow-md:   rgba(26,107,60,.14);
}

*,
*::before,
*::after {
  box-sizing: border-box;
}

html,
body {
  max-width: 100%;
  overflow-x: hidden;
}

button,
input,
select,
textarea {
  width: 100%;
  max-width: 100%;
  box-sizing: border-box;
}

/* ── TOPBAR ── */
.exam-bar {
  background: var(--white);
  border-bottom: 1.5px solid var(--border);
  padding: 0 28px;
  height: 62px;
  display: flex; align-items: center; gap: 16px;
  position: sticky; top: 0; z-index: 100;
  box-shadow: 0 2px 12px rgba(0,0,0,.04);
  margin: -24px -24px 0;
}
.eb-logo { display: flex; align-items: center; gap: 9px; margin-right: 12px; }
.logo-mark {
  width: 32px; height: 32px; background: var(--green);
  border-radius: 8px; display: grid; place-items: center;
  font-family: 'Playfair Display', serif; font-size: clamp(.8rem, 2vw, .875rem); font-weight: 800; color: #fff;
  box-shadow: 0 2px 8px var(--shadow-md);
}
.eb-title { font-family: 'Playfair Display', serif; font-size: clamp(.82rem, 3.5vw, .95rem); font-weight: 700; color: var(--text); }
.eb-course { font-size: clamp(.72rem, 2.8vw, .78rem); color: var(--text-muted); margin-left: 4px; }
.eb-sep { width: 1.5px; height: 28px; background: var(--border); margin: 0 4px; }

.timer-wrap {
  display: flex; align-items: center; gap: 8px;
  background: var(--green-light); border: 1.5px solid rgba(26,107,60,.25);
  padding: 7px 16px; border-radius: 50px;
  transition: background .4s, border-color .4s;
}
.timer-wrap.warning { background: var(--amber-light); border-color: rgba(217,119,6,.3); }
.timer-wrap.danger  { background: var(--red-light);   border-color: rgba(220,38,38,.3); animation: timerShake .5s infinite; }
@keyframes timerShake { 0%,100%{transform:none} 25%{transform:translateX(-2px)} 75%{transform:translateX(2px)} }
.timer-icon { font-size: clamp(.78rem, 3vw, .9rem); }
#timer-display {
  font-family: 'DM Mono', monospace; font-size: clamp(.82rem, 3.5vw, 1rem); font-weight: 500;
  color: var(--green); letter-spacing: .05em;
  transition: color .4s;
}
.timer-wrap.warning #timer-display { color: var(--amber); }
.timer-wrap.danger  #timer-display { color: var(--red); }

.eb-progress {
  flex: 1; display: flex; align-items: center; gap: 10px;
  max-width: 320px; margin: 0 auto;
}
.prog-bar-track { flex: 1; height: 6px; background: var(--gray-200); border-radius: 3px; overflow: hidden; }
.prog-bar-fill  { height: 100%; background: var(--green); border-radius: 3px; transition: width .4s ease; }
.prog-label { font-size: clamp(.68rem, 2.5vw, .75rem); font-weight: 700; color: var(--text-muted); white-space: nowrap; }

.btn-submit-exam {
  background: var(--green); color: #fff; border: none;
  padding: 9px 22px; border-radius: 9px;
  font-family: 'DM Sans', sans-serif; font-size: clamp(.76rem, 3vw, .85rem); font-weight: 700;
  cursor: pointer; transition: all .2s; white-space: nowrap;
  box-shadow: 0 3px 10px var(--shadow-md);
}
.btn-submit-exam:hover { background: var(--green-mid); }

/* ── BODY LAYOUT ── */
.exam-body {
  display: flex; min-height: calc(100vh - 62px - 80px);
}

/* ── QUESTION PANEL ── */
.q-panel {
  flex: 1; padding: 32px 36px; min-width: 0; max-width: 780px;
}
.q-header {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 22px;
}
.q-num-badge {
  display: inline-flex; align-items: center; gap: 8px;
  background: var(--green); color: #fff; padding: 6px 16px; border-radius: 50px;
  font-size: clamp(.7rem, 3vw, .78rem); font-weight: 700; letter-spacing: .04em;
}
.q-marks { font-size: clamp(.72rem, 2.8vw, .8rem); color: var(--text-muted); font-weight: 600; }
.q-marks strong { color: var(--green); }
.q-section-label {
  font-size: clamp(.64rem, 2.6vw, .7rem); font-weight: 700; text-transform: uppercase; letter-spacing: .1em;
  color: var(--gray-400); margin-bottom: 12px;
}
.q-text {
  font-size: clamp(.94rem, 3.7vw, 1.06rem); color: var(--text); line-height: 1.75;
  margin-bottom: 28px; font-weight: 500;
}

/* ── OPTIONS ── */
.options { display: flex; flex-direction: column; gap: 11px; margin-bottom: 30px; }
.mcq-option { display: flex; align-items: flex-start; gap: 14px; }
.mcq-option input[type="radio"] { display: none; }
.mcq-option label {
  display: flex; align-items: flex-start; gap: 14px;
  cursor: pointer; width: 100%;
}
.option-letter {
  flex-shrink: 0; width: 42px; height: 42px; border-radius: 11px;
  border: 2px solid var(--border); background: var(--white);
  display: flex; align-items: center; justify-content: center;
  font-size: clamp(.72rem, 3vw, .82rem); font-weight: 800; color: var(--gray-400);
  transition: all .18s; margin-top: 1px;
}
.option-text {
  flex: 1; background: var(--white); border: 1.5px solid var(--border);
  border-radius: 12px; padding: 14px 18px;
  font-size: clamp(.86rem, 3.4vw, .95rem); color: var(--gray-700); line-height: 1.55;
  transition: all .18s; cursor: pointer;
}
.mcq-option label:hover .option-text   { border-color: var(--green); background: var(--green-pale); color: var(--text); }
.mcq-option label:hover .option-letter { border-color: var(--green); color: var(--green); }
.mcq-option input:checked + label .option-letter { background: var(--green); border-color: var(--green); color: #fff; }
.mcq-option input:checked + label .option-text   { border-color: var(--green); background: var(--green-light); color: var(--text); font-weight: 600; }

.fill-option {
  width: 100%; padding: 14px 18px; border-radius: 12px;
  border: 1.5px solid var(--border); background: var(--white);
  font-family: 'DM Sans', sans-serif; font-size: clamp(.86rem, 3.4vw, .95rem); color: var(--text);
  outline: none; transition: border-color .18s;
  margin-bottom: 30px;
}
.fill-option:focus { border-color: var(--green); }

/* ── NAV BUTTONS ── */
.q-actions { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
.btn-nav {
  padding: 11px 24px; border-radius: 10px; font-size: clamp(.76rem, 3.1vw, .88rem); font-weight: 700;
  border: 1.5px solid var(--border); background: var(--white); color: var(--gray-700);
  cursor: pointer; font-family: 'DM Sans', sans-serif; transition: all .18s;
}
.btn-nav:hover { background: var(--gray-100); }
.btn-nav.primary {
  background: var(--green); color: #fff; border-color: var(--green);
  box-shadow: 0 3px 10px var(--shadow-md);
}
.btn-nav.primary:hover { background: var(--green-mid); }
.btn-nav:disabled { opacity: .4; cursor: not-allowed; }

.flag-btn {
  padding: 10px 16px; border-radius: 10px; font-size: clamp(.74rem, 3vw, .82rem); font-weight: 700;
  border: 1.5px solid var(--amber-light); background: var(--amber-light); color: var(--amber);
  cursor: pointer; font-family: 'DM Sans', sans-serif; transition: all .18s;
  margin-left: auto;
}
.flag-btn:hover { opacity: .85; }
.btn-quit {
  padding: 10px 16px; border-radius: 10px; font-size: clamp(.74rem, 3vw, .82rem); font-weight: 600;
  border: 1.5px solid var(--border); background: var(--white); color: var(--gray-600);
  cursor: pointer; font-family: 'DM Sans', sans-serif; transition: all .18s;
}
.btn-quit:hover { background: var(--gray-50); }

/* ── SIDEBAR WRAPPER ── */
.sidebar-wrapper {
  position: sticky;
  top: 62px;
  height: calc(100vh - 62px);
  flex-shrink: 0;
  display: flex;
  align-items: stretch;
  overflow: visible;   /* ← critical: lets button stick out to the left */
}

/* ── SIDEBAR PANEL ── */
.sidebar-panel {
  position: relative;
  width: 300px;
  height: 100%;
  border-left: 1.5px solid var(--border);
  background: var(--white);
  padding: 24px 20px;
  overflow-y: auto;
  transition: width .3s ease, padding .3s ease;
}
.sidebar-panel.collapsed {
  width: 0;
  padding: 0;
  overflow: hidden;
  border-left: none;
}

/* ── TOGGLE BUTTON — sibling of sidebar-panel, inside wrapper ── */
.sidebar-toggle-btn {
  position: relative;    /* normal flow inside wrapper flex */
  align-self: center;
  width: 18px;
  height: 48px;
  border-radius: 6px 0 0 6px;
  border: 1.5px solid var(--border);
  border-right: none;
  background: var(--white);
  color: var(--gray-500);
  font-size: clamp(.64rem, 2.5vw, .7rem);
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all .2s;
  z-index: 10;
  box-shadow: -2px 0 6px var(--shadow);
  flex-shrink: 0;
}
.sidebar-toggle-btn:hover {
  background: var(--green-light);
  border-color: var(--green);
  color: var(--green);
}

.sidebar-inner {
  transition: opacity .2s ease;
  min-width: 260px;
}
.sidebar-panel.collapsed .sidebar-inner {
  opacity: 0;
  pointer-events: none;
}


.sp-title {
  font-family: 'Playfair Display', serif; font-size: clamp(.84rem, 3vw, .95rem); font-weight: 700;
  color: var(--text); margin-bottom: 16px;
}
.q-grid {
  display: grid; grid-template-columns: repeat(5, 1fr);
  gap: 7px; margin-bottom: 22px;
}
.q-dot {
  aspect-ratio: 1; border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  font-size: clamp(.66rem, 2.6vw, .73rem); font-weight: 700; cursor: pointer;
  border: 1.5px solid var(--border); color: var(--gray-500); background: var(--white);
  transition: all .15s;
}
.q-dot:hover    { border-color: var(--green); color: var(--green); }
.q-dot.answered { background: var(--green); border-color: var(--green); color: #fff; }
.q-dot.flagged  { background: var(--amber-light); border-color: var(--amber); color: var(--amber); }
.q-dot.current  { border-color: var(--green); box-shadow: 0 0 0 3px rgba(26,107,60,.15); }

.legend { display: flex; flex-direction: column; gap: 8px; margin-bottom: 22px; }
.leg-item { display: flex; align-items: center; gap: 9px; font-size: clamp(.7rem, 2.6vw, .77rem); color: var(--gray-600); }
.leg-dot { width: 14px; height: 14px; border-radius: 4px; flex-shrink: 0; }

.sp-summary {
  background: var(--gray-50); border: 1.5px solid var(--border);
  border-radius: 12px; padding: 16px;
}
.sp-summary h4 { font-size: clamp(.72rem, 2.8vw, .8rem); font-weight: 700; color: var(--text); margin-bottom: 12px; }
.sp-row {
  display: flex; justify-content: space-between; font-size: clamp(.72rem, 2.8vw, .8rem);
  padding: 5px 0; border-bottom: 1px solid var(--border);
}
.sp-row:last-child { border: none; }
.sp-row span:last-child { font-weight: 700; color: var(--text); }

/* ── MODAL ── */
.modal-overlay {
  display: none; position: fixed; inset: 0; z-index: 999;
  background: rgba(0,0,0,.4); backdrop-filter: blur(4px);
  align-items: center; justify-content: center; padding: 20px;
}
.modal-overlay.open { display: flex; }
.modal-box {
  background: var(--white); border: 1.5px solid var(--border);
  border-radius: 20px; padding: 36px 38px; max-width: 420px; width: 100%;
  animation: fadeUp .3s ease; text-align: center;
  box-shadow: 0 20px 60px rgba(0,0,0,.15);
}
@keyframes fadeUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:none} }
.modal-icon   { font-size: clamp(2rem, 8vw, 2.5rem); margin-bottom: 14px; }
.modal-box h3 { font-family: 'Playfair Display', serif; font-size: clamp(1.08rem, 5vw, 1.3rem); font-weight: 700; color: var(--text); margin-bottom: 10px; }
.modal-box p  { font-size: clamp(.82rem, 3.4vw, .9rem); color: var(--text-muted); margin-bottom: 24px; line-height: 1.6; }
.modal-btns   { display: flex; gap: 10px; }
.btn-cancel {
  flex: 1; background: none; border: 1.5px solid var(--border); color: var(--gray-600);
  padding: 11px; border-radius: 10px; font-size: clamp(.8rem, 3.2vw, .88rem); font-weight: 700;
  cursor: pointer; font-family: 'DM Sans', sans-serif; transition: all .18s;
}
.btn-cancel:hover { background: var(--gray-100); }
.btn-confirm {
  flex: 1; background: var(--green); color: #fff; border: none;
  padding: 11px; border-radius: 10px; font-size: clamp(.8rem, 3.2vw, .88rem); font-weight: 700;
  cursor: pointer; font-family: 'DM Sans', sans-serif; transition: all .2s;
  box-shadow: 0 3px 10px var(--shadow-md);
}
.btn-confirm:hover { background: var(--green-mid); }

/* ── TOAST ── */
.exam-toast {
  position: fixed; bottom: 24px; right: 24px; z-index: 9999;
  background: var(--white); border: 1.5px solid var(--border);
  color: var(--text); padding: 13px 18px; border-radius: 12px;
  font-size: clamp(.78rem, 3.2vw, .85rem); font-weight: 600;
  box-shadow: 0 8px 32px rgba(0,0,0,.12);
  transform: translateY(70px); opacity: 0; transition: all .3s;
  max-width: 280px; display: flex; align-items: center; gap: 9px;
}
.exam-toast.show { transform: translateY(0); opacity: 1; }

.quick-row {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 12px;
  width: 100%;
}

.calendar-grid {
  display: grid;
  grid-template-columns: minmax(0, 1fr);
  gap: 14px;
  width: 100%;
}

.streak-card {
  display: flex;
  flex-direction: row;
  align-items: center;
  gap: 10px;
  min-width: 0;
  font-size: clamp(.72rem, 3vw, .9rem);
}

.welcome-strip {
  display: flex;
  flex-direction: column;
  gap: 14px;
  width: 100%;
}

.review-ticker-wrap {
  display: none;
}

.exam-bar,
.exam-body,
.q-panel,
.q-header,
.options,
.mcq-option,
.mcq-option label,
.option-text,
.q-actions,
.modal-box,
.modal-btns,
.exam-toast {
  min-width: 0;
}

.exam-bar {
  height: auto;
  min-height: 62px;
  flex-wrap: wrap;
  padding: 10px 14px;
  gap: 8px;
  margin: -24px -16px 0;
}

.eb-logo {
  margin-right: 0;
}

.eb-title {
  font-size: clamp(.82rem, 3.5vw, .95rem);
}

.eb-course,
.prog-label {
  display: none;
}

.eb-sep {
  height: 22px;
  margin: 0;
}

.timer-wrap {
  flex: 1 1 116px;
  justify-content: center;
  padding: 7px 10px;
}

.timer-icon {
  font-size: clamp(.78rem, 3vw, .9rem);
}

#timer-display {
  font-size: clamp(.82rem, 3.5vw, 1rem);
}

.eb-progress {
  order: 10;
  flex: 1 0 100%;
  max-width: none;
  margin: 0;
}

.btn-submit-exam {
  flex: 1 1 120px;
  padding: 9px 12px;
  font-size: clamp(.76rem, 3vw, .85rem);
}

.exam-body {
  display: block;
  min-height: calc(100vh - 62px);
  width: 100%;
  overflow-x: hidden;
}

.q-panel {
  width: 100%;
  max-width: none;
  padding: 20px 16px;
}

.q-header {
  align-items: flex-start;
  gap: 10px;
}

.q-num-badge {
  padding: 6px 12px;
  font-size: clamp(.7rem, 3vw, .78rem);
}

.q-marks {
  font-size: clamp(.72rem, 2.8vw, .8rem);
  white-space: nowrap;
}

.q-section-label {
  font-size: clamp(.64rem, 2.6vw, .7rem);
}

.q-text {
  overflow-wrap: anywhere;
  font-size: clamp(.94rem, 3.7vw, 1.06rem);
  line-height: 1.68;
}

.mcq-option {
  gap: 10px;
}

.mcq-option label {
  gap: 10px;
}

.option-letter {
  width: 34px;
  height: 34px;
  border-radius: 9px;
  font-size: clamp(.72rem, 3vw, .82rem);
}

.option-text {
  padding: 12px 13px;
  overflow-wrap: anywhere;
  font-size: clamp(.86rem, 3.4vw, .95rem);
}

.fill-option {
  font-size: clamp(.86rem, 3.4vw, .95rem);
}

.q-actions {
  display: grid;
  grid-template-columns: minmax(0, 1fr);
  gap: 10px;
}

.btn-nav,
.flag-btn,
.btn-quit {
  width: 100%;
  margin-left: 0;
  padding: 11px 12px;
  font-size: clamp(.76rem, 3.1vw, .88rem);
}

.sidebar-wrapper {
  display: none;
}

.modal-overlay {
  padding: 14px;
}

.modal-box {
  border-radius: 16px;
  padding: 26px 18px;
}

.modal-icon {
  font-size: clamp(2rem, 8vw, 2.5rem);
}

.modal-box h3 {
  font-size: clamp(1.08rem, 5vw, 1.3rem);
}

.modal-box p {
  font-size: clamp(.82rem, 3.4vw, .9rem);
}

.modal-btns {
  flex-direction: column;
}

.btn-cancel,
.btn-confirm {
  font-size: clamp(.8rem, 3.2vw, .88rem);
}

.exam-toast {
  right: 14px;
  bottom: 14px;
  left: 14px;
  max-width: none;
  font-size: clamp(.78rem, 3.2vw, .85rem);
}

@media (min-width: 480px) {
  .review-ticker-wrap {
    display: block;
  }

  .q-actions {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }

  .modal-btns {
    flex-direction: row;
  }
}

@media (min-width: 768px) {
  input,
  select,
  textarea,
  .fill-option {
    width: 100%;
  }

  .welcome-strip {
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
  }

  .exam-bar {
    height: 62px;
    flex-wrap: nowrap;
    padding: 0 24px;
    gap: 14px;
    margin: -24px -24px 0;
  }

  .eb-course,
  .prog-label {
    display: inline;
  }

  .eb-sep {
    height: 28px;
    margin: 0 4px;
  }

  .timer-wrap {
    flex: 0 0 auto;
    padding: 7px 16px;
  }

  .eb-progress {
    order: 0;
    flex: 1 1 auto;
    max-width: 320px;
    margin: 0 auto;
  }

  .btn-submit-exam {
    flex: 0 0 auto;
    max-width: max-content;
    padding: 9px 22px;
  }

  .exam-body {
    display: flex;
    min-height: calc(100vh - 62px - 80px);
  }

  .q-panel {
    flex: 1;
    max-width: 780px;
    padding: 28px 26px;
  }

  .option-letter {
    width: 40px;
    height: 40px;
  }

  .option-text {
    padding: 14px 16px;
  }

  .q-actions {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
  }

  .btn-nav,
  .flag-btn,
  .btn-quit {
    max-width: max-content;
  }

  .flag-btn {
    margin-left: auto;
  }

  .sidebar-wrapper {
    display: flex;
  }
}

@media (min-width: 1024px) {
  .quick-row {
    grid-template-columns: repeat(4, minmax(0, 1fr));
  }

  .calendar-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }

  .exam-bar {
    padding: 0 28px;
  }

  .q-panel {
    padding: 32px 36px;
  }

  .option-letter {
    width: 42px;
    height: 42px;
  }

  .option-text {
    padding: 14px 18px;
  }
}
</style>

{{-- ── TOPBAR ── --}}
<div class="exam-bar">
 
  <div class="eb-sep"></div>
  <div>
    <div class="eb-title">{{ $course->course_code }}</div>
  </div>
  <div class="eb-sep"></div>

  <div class="timer-wrap" id="timer-wrap">
    <span class="timer-icon">⏱</span>
    <span id="timer-display">{{ sprintf('%02d', $duration) }}:00</span>
  </div>

  <div class="eb-progress">
    <div class="prog-bar-track"><div class="prog-bar-fill" id="top-progress" style="width:0%"></div></div>
    <span class="prog-label" id="top-prog-label">0 / {{ $questions->count() }}</span>
  </div>

  <button class="btn-submit-exam" onclick="openSubmitModal()">Submit Exam</button>
</div>

{{-- ── EXAM BODY ── --}}
<div class="exam-body">

  {{-- Question panel --}}
  <div class="q-panel" id="q-panel"></div>

  {{-- Sidebar wrapper (sticky) --}}
  <div class="sidebar-wrapper">

  {{-- Toggle tab OUTSIDE sidebar-panel so it's never clipped --}}
  <button class="sidebar-toggle-btn" id="sidebar-toggle" type="button"
          title="Toggle Navigator" onclick="toggleExamSidebar()">
    <span id="toggle-icon">⟩</span>
  </button>

  <div class="sidebar-panel" id="sidebar-panel">
    <div class="sidebar-inner" id="sidebar-inner">
      <div class="sp-title">Question Navigator</div>
      <div class="q-grid" id="q-grid"></div>

      <div class="legend">
        <div class="leg-item">
          <div class="leg-dot" style="background:var(--green);"></div> Answered
        </div>
        <div class="leg-item">
          <div class="leg-dot" style="background:var(--amber);border:1.5px solid var(--amber);"></div> Flagged
        </div>
        <div class="leg-item">
          <div class="leg-dot" style="background:var(--white);border:1.5px solid var(--border);"></div> Not Answered
        </div>
      </div>

      <div class="sp-summary">
        <h4>📊 Session Summary</h4>
        <div class="sp-row"><span>Total Questions</span><span>{{ $questions->count() }}</span></div>
        <div class="sp-row"><span>Answered</span><span id="s-answered" style="color:var(--green);">0</span></div>
        <div class="sp-row"><span>Not Answered</span><span id="s-unanswered" style="color:var(--red);">{{ $questions->count() }}</span></div>
        <div class="sp-row"><span>Flagged</span><span id="s-flagged" style="color:var(--amber);">0</span></div>
      </div>
    </div>
  </div>

</div>

</div>

{{-- ── HIDDEN FORM ── --}}
<form method="POST" action="{{ route('mock.submit') }}" id="examForm" style="display:none">
  @csrf
  <div id="hidden-answers"></div>
  <input type="hidden" name="time_used" id="time-used-input">
</form>

{{-- ── SUBMIT MODAL ── --}}
<div class="modal-overlay" id="submit-modal">
  <div class="modal-box">
    <div class="modal-icon">📤</div>
    <h3>Submit Exam?</h3>
    <p id="modal-msg">Are you sure you want to submit?</p>
    <div class="modal-btns">
      <button class="btn-cancel" onclick="closeModal()">Cancel</button>
      <button class="btn-confirm" onclick="submitExam()">Yes, Submit</button>
    </div>
  </div>
</div>

{{-- ── QUIT MODAL ── --}}
<div class="modal-overlay" id="quit-modal">
  <div class="modal-box">
    <div class="modal-icon">🚪</div>
    <h3>Quit Exam?</h3>
    <p>Are you sure you want to quit? Your progress will be lost.</p>
    <div class="modal-btns">
      <button class="btn-cancel" onclick="document.getElementById('quit-modal').classList.remove('open')">Stay</button>
      <button class="btn-confirm" style="background:var(--red);" onclick="window.location.href='{{ route('mock.index') }}'">Quit</button>
    </div>
  </div>
</div>

{{-- ── TOAST ── --}}
<div class="exam-toast" id="exam-toast">
  <span id="t-icon"></span><span id="t-msg"></span>
</div>

@push('scripts')
<script>
const QUESTIONS = @json($questions);
const TOTAL      = QUESTIONS.length;
const DURATION   = {{ $duration }};
const START_TIME = Math.floor(Date.now() / 1000);

let current  = 0;
let answers  = new Array(TOTAL).fill(null);
let flagged  = new Array(TOTAL).fill(false);
let timerInt = null;

buildGrid();
renderQuestion(0);
startTimer();

window.onbeforeunload = () => 'Are you sure you want to leave? Your progress will be lost.';

// ── TIMER ──
function startTimer() {
  timerInt = setInterval(() => {
    const elapsed = Math.floor(Date.now() / 1000) - START_TIME;
    const left    = (DURATION * 60) - elapsed;
    if (left <= 0) {
      clearInterval(timerInt);
      showToast('⏰ Time is up! Auto-submitting…');
      setTimeout(submitExam, 1400);
      return;
    }
    const m = Math.floor(left / 60);
    const s = left % 60;
    document.getElementById('timer-display').textContent =
      String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
    const tw = document.getElementById('timer-wrap');
    if      (left <= 60)  tw.className = 'timer-wrap danger';
    else if (left <= 300) tw.className = 'timer-wrap warning';
    else                  tw.className = 'timer-wrap';
  }, 1000);
}

// ── RENDER QUESTION ──
function renderQuestion(idx) {
  current = idx;
  const q       = QUESTIONS[idx];
  const letters = ['A','B','C','D'];
  const opts    = [q.option_a, q.option_b, q.option_c, q.option_d].filter(Boolean);
  const isLast  = idx === TOTAL - 1;

  let bodyHTML = '';
  if (q.question_type === 'mcq') {
    let optsHTML = opts.map((opt, i) => {
      const val     = letters[i];
      const checked = answers[idx] === val ? 'checked' : '';
      return `
        <div class="mcq-option">
          <input type="radio" name="q_${idx}" id="q${idx}_${val}" value="${val}" ${checked}
                 onchange="selectAnswer(${idx}, '${val}')">
          <label for="q${idx}_${val}">
            <span class="option-letter">${val}</span>
            <span class="option-text">${opt}</span>
          </label>
        </div>`;
    }).join('');
    bodyHTML = `<div class="options">${optsHTML}</div>`;
  } else {
    bodyHTML = `<input type="text" class="fill-option" id="fill_${idx}"
                  value="${answers[idx] || ''}"
                  placeholder="Type your answer here…"
                  oninput="selectAnswer(${idx}, this.value)">`;
  }

  const flagClass = flagged[idx] ? 'flagged' : '';
  const flagText  = flagged[idx] ? '🚩 Flagged' : '🏳 Flag';

  document.getElementById('q-panel').innerHTML = `
    <div class="q-header">
      <div class="q-num-badge">Question ${idx + 1} of ${TOTAL}</div>
      <div class="q-marks">Marks: <strong>4</strong></div>
    </div>
    <div class="q-section-label">Section A — Multiple Choice</div>
    <div class="q-text">${q.question}</div>
    ${bodyHTML}
    <div class="q-actions">
      <button class="btn-nav" onclick="navigate(-1)" ${idx === 0 ? 'disabled' : ''}>← Previous</button>
      <button class="btn-nav ${isLast ? '' : 'primary'}" onclick="navigate(1)" ${isLast ? 'disabled' : ''}>
        ${isLast ? 'Last Question' : 'Next →'}
      </button>
      ${isLast ? `<button class="btn-nav primary" onclick="openSubmitModal()">📤 Submit Exam</button>` : ''}
      <button class="flag-btn ${flagClass}" id="flag-btn" onclick="toggleFlag(${idx})">${flagText}</button>
      <button class="btn-quit" onclick="document.getElementById('quit-modal').classList.add('open')">← Quit</button>
    </div>`;

  updateGrid();
  updateProgress();
}

function selectAnswer(idx, val) {
  answers[idx] = val;
  updateGrid();
  updateProgress();
}

function navigate(dir) {
  const next = current + dir;
  if (next >= 0 && next < TOTAL) renderQuestion(next);
}

function toggleFlag(idx) {
  flagged[idx] = !flagged[idx];
  const btn = document.getElementById('flag-btn');
  if (btn) {
    btn.className = 'flag-btn' + (flagged[idx] ? ' flagged' : '');
    btn.textContent = flagged[idx] ? '🚩 Flagged' : '🏳 Flag';
  }
  showToast(flagged[idx] ? '🚩 Question flagged for review' : '✅ Flag removed');
  updateGrid();
}

function buildGrid() {
  document.getElementById('q-grid').innerHTML =
    QUESTIONS.map((_, i) =>
      `<div class="q-dot" id="dot-${i}" onclick="renderQuestion(${i})">${i + 1}</div>`
    ).join('');
}

function updateGrid() {
  QUESTIONS.forEach((_, i) => {
    const dot = document.getElementById('dot-' + i);
    if (!dot) return;
    let cls = 'q-dot';
    if (i === current)       cls += ' current';
    if (flagged[i])          cls += ' flagged';
    else if (answers[i] !== null && answers[i] !== '') cls += ' answered';
    dot.className = cls;
  });
  const ans = answers.filter(a => a !== null && a !== '').length;
  const fl  = flagged.filter(Boolean).length;
  document.getElementById('s-answered').textContent   = ans;
  document.getElementById('s-unanswered').textContent = TOTAL - ans;
  document.getElementById('s-flagged').textContent    = fl;
}

function updateProgress() {
  const ans = answers.filter(a => a !== null && a !== '').length;
  const pct = (ans / TOTAL) * 100;
  document.getElementById('top-progress').style.width = pct + '%';
  document.getElementById('top-prog-label').textContent = `${ans} / ${TOTAL}`;
}

function openSubmitModal() {
  const unanswered = answers.filter(a => a === null || a === '').length;
  document.getElementById('modal-msg').textContent = unanswered > 0
    ? `You have ${unanswered} unanswered question${unanswered > 1 ? 's' : ''}. Are you sure you want to submit?`
    : `You have answered all ${TOTAL} questions. Ready to submit?`;
  document.getElementById('submit-modal').classList.add('open');
}

function closeModal() {
  document.getElementById('submit-modal').classList.remove('open');
}

function submitExam() {
  clearInterval(timerInt);
  window.onbeforeunload = null;
  const elapsedSeconds = Math.floor(Date.now() / 1000) - START_TIME;
  document.getElementById('time-used-input').value = elapsedSeconds;
  const container = document.getElementById('hidden-answers');
  container.innerHTML = '';
  QUESTIONS.forEach((q, i) => {
    if (answers[i] !== null && answers[i] !== '') {
      const inp = document.createElement('input');
      inp.type  = 'hidden';
      inp.name  = `answers[${q.id}]`;
      inp.value = answers[i];
      container.appendChild(inp);
    }
  });
  document.getElementById('examForm').submit();
}

function showToast(msg) {
  const t = document.getElementById('exam-toast');
  const parts = msg.split(' ');
  document.getElementById('t-icon').textContent = parts[0];
  document.getElementById('t-msg').textContent  = parts.slice(1).join(' ');
  t.classList.add('show');
  clearTimeout(t._t);
  t._t = setTimeout(() => t.classList.remove('show'), 3000);
}

// ── SIDEBAR TOGGLE ──
function toggleExamSidebar() {
  const panel = document.getElementById('sidebar-panel');
  const icon  = document.getElementById('toggle-icon');
  const collapsed = panel.classList.toggle('collapsed');
  icon.textContent = collapsed ? '⟨' : '⟩';
}
</script>
@endpush
@endsection
