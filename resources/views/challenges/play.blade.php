@extends('layouts.dashboard')

@section('title')
@section('page-title')
@endsection

@section('dashboard-content')
<style>
:root {
  --green:       #1A6B3C;
  --green-light: #E8F5EE;
  --white:       #FFFFFF;
  --border:      #DDE8E1;
  --text:        #1C2B1E;
  --gray-500:    #6B7280;
  --gray-700:    #374151;
  --shadow-md:   rgba(26,107,60,.14);
}
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
.timer-wrap {
  display: flex; align-items: center; gap: 8px;
  background: var(--green-light); border: 1.5px solid rgba(26,107,60,.25);
  padding: 7px 16px; border-radius: 50px;
}
.btn-submit-exam {
  background: var(--green); color: #fff; border: none;
  padding: 9px 22px; border-radius: 9px;
  font-family: 'DM Sans', sans-serif; font-size: .9rem; font-weight: 700;
  cursor: pointer;
  box-shadow: 0 3px 10px var(--shadow-md);
}
.exam-body { display:flex; min-height: calc(100vh - 62px - 80px); }
.q-panel { flex: 1; padding: 32px 36px; min-width: 0; max-width: 780px; }
.q-num-badge {
  display: inline-flex; align-items: center; gap: 8px;
  background: var(--green); color: #fff; padding: 6px 16px; border-radius: 50px;
  font-size: .85rem; font-weight: 700;
}
.q-text { font-size: 1.05rem; color: var(--text); line-height: 1.7; margin-bottom: 28px; font-weight: 500; }
.q-actions { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
.btn-nav {
  padding: 11px 24px; border-radius: 10px;
  border: 1.5px solid var(--border); background: #fff; color: var(--gray-700);
  cursor: pointer; font-family: 'DM Sans', sans-serif; font-weight: 700;
}
.btn-nav.primary { background: var(--green); color: #fff; border-color: var(--green); }
.options { display: flex; flex-direction: column; gap: 11px; }
.mcq-option { display: flex; align-items: flex-start; gap: 14px; }
.mcq-option input[type="radio"] { display: none; }
.mcq-option label { display:flex; align-items:flex-start; gap:14px; cursor:pointer; width:100%; }
.option-letter {
  flex-shrink: 0; width: 42px; height: 42px; border-radius: 11px;
  border: 2px solid var(--border); background: var(--white);
  display: flex; align-items: center; justify-content: center;
  font-weight: 800; color: var(--gray-500);
}
.option-text {
  flex: 1; background: var(--white); border: 1.5px solid var(--border);
  border-radius: 12px; padding: 14px 18px;
  font-size: .95rem; color: var(--gray-700); line-height: 1.55;
}
.fill-option {
  width: 100%; padding: 14px 18px; border-radius: 12px;
  border: 1.5px solid var(--border); background: var(--white);
  outline: none;
  font-family: 'DM Sans', sans-serif; font-size: .95rem; color: var(--text);
}
.modal-overlay {
  display: none; position: fixed; inset: 0; z-index: 999;
  background: rgba(0,0,0,.4); backdrop-filter: blur(4px);
  align-items: center; justify-content: center; padding: 20px;
}
.modal-overlay.open { display: flex; }
.modal-box {
  background: var(--white); border: 1.5px solid var(--border);
  border-radius: 20px; padding: 26px 18px; max-width: 420px; width: 100%;
  box-shadow: 0 20px 60px rgba(0,0,0,.15);
  text-align: center;
}
.modal-box h3 { margin: 0 0 10px; font-family: 'Playfair Display', serif; }
.modal-box p { margin: 0 0 18px; color: var(--gray-500); line-height: 1.6; }
.modal-btns { display: flex; gap: 10px; }
.btn-cancel { flex:1; background:none; border:1.5px solid var(--border); color: var(--gray-500); padding: 11px; border-radius: 10px; font-weight: 700; }
.btn-confirm { flex:1; background: var(--green); color:#fff; border:none; padding: 11px; border-radius: 10px; font-weight: 700; }
</style>

<div class="exam-bar">
  <div style="flex:1;">
    <div style="font-family:'Playfair Display',serif;font-weight:800;color:var(--text);">
      Study Challenge — {{ $role === 'opponent' ? 'Opponent' : 'You' }}
    </div>
    <div style="font-size:.78rem;color:var(--gray-500);margin-top:2px;">
      Same question set for both players
    </div>
  </div>

  <div class="timer-wrap">
    <span>⏱</span>
    <span id="timer-display">{{ sprintf('%02d', (int)$duration) }}:00</span>
  </div>

  <button class="btn-submit-exam" type="button" onclick="openSubmitModal()">Submit Challenge</button>
</div>

<div class="exam-body">
  <div class="q-panel" id="q-panel"></div>
</div>

<form method="POST" id="examForm" style="display:none" action="{{ $role === 'challenger' ? route('challenge.challenger-submit', ['challenge' => $challenge->id]) : route('challenge.opponent-submit', ['challenge' => $challenge->id]) }}">
  @csrf
  <div id="hidden-answers"></div>
  <input type="hidden" name="time_used" id="time-used-input">
</form>

<div class="modal-overlay" id="submit-modal">
  <div class="modal-box">
    <div style="font-size:2.2rem;margin-bottom:10px;">📤</div>
    <h3>Submit Challenge?</h3>
    <p id="modal-msg">Are you sure you want to submit?</p>
    <div class="modal-btns">
      <button class="btn-cancel" type="button" onclick="closeModal()">Cancel</button>
      <button class="btn-confirm" type="button" onclick="submitExam()">Yes, Submit</button>
    </div>
  </div>
</div>

@push('scripts')
<script>
const QUESTIONS = @json($questions);
const TOTAL = QUESTIONS.length;
const DURATION = {{ (int) $duration }};
const START_TIME = Math.floor(Date.now() / 1000);
let current = 0;
let answers = new Array(TOTAL).fill(null);
let timerInt = null;

function startTimer() {
  timerInt = setInterval(() => {
    const elapsed = Math.floor(Date.now() / 1000) - START_TIME;
    const left = (DURATION * 60) - elapsed;
    if (left <= 0) {
      clearInterval(timerInt);
      setTimeout(submitExam, 800);
      return;
    }
    const m = Math.floor(left / 60);
    const s = left % 60;
    document.getElementById('timer-display').textContent = String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
  }, 1000);
}

function renderQuestion(idx) {
  current = idx;
  const q = QUESTIONS[idx];
  const letters = ['A','B','C','D'];

  if ((q.question_type || '') === 'mcq') {
    const opts = [q.option_a, q.option_b, q.option_c, q.option_d]
      .filter(v => v !== null && v !== undefined && v !== '');

    const optsHTML = opts.map((opt, i) => {
      const val = letters[i];
      const checked = answers[idx] === val ? 'checked' : '';
      return `
        <div class="mcq-option">
          <input type="radio" name="q_${idx}" id="q${idx}_${val}" value="${val}" ${checked} onchange="selectAnswer(${idx}, '${val}')">
          <label for="q${idx}_${val}">
            <span class="option-letter">${val}</span>
            <span class="option-text">${opt}</span>
          </label>
        </div>
      `;
    }).join('');

    document.getElementById('q-panel').innerHTML = `
      <div class="q-num-badge">Question ${idx + 1} of ${TOTAL}</div>
      <div class="q-text">${q.question}</div>
      <div class="options">${optsHTML}</div>
      <div class="q-actions">
        <button class="btn-nav" type="button" ${idx===0?'disabled':''} onclick="navigate(-1)">← Previous</button>
        ${idx === TOTAL-1 ? `<button class="btn-nav primary" type="button" onclick="openSubmitModal()">📤 Submit Challenge</button>` : `<button class="btn-nav primary" type="button" onclick="navigate(1)">Next →</button>`}
      </div>
    `;
  } else {
    document.getElementById('q-panel').innerHTML = `
      <div class="q-num-badge">Question ${idx + 1} of ${TOTAL}</div>
      <div class="q-text">${q.question}</div>
      <input class="fill-option" type="text" id="fill_${idx}" value="${answers[idx] || ''}" placeholder="Type your answer…" oninput="selectAnswer(${idx}, this.value)">
      <div class="q-actions">
        <button class="btn-nav" type="button" ${idx===0?'disabled':''} onclick="navigate(-1)">← Previous</button>
        ${idx === TOTAL-1 ? `<button class="btn-nav primary" type="button" onclick="openSubmitModal()">📤 Submit Challenge</button>` : `<button class="btn-nav primary" type="button" onclick="navigate(1)">Next →</button>`}
      </div>
    `;
  }
}

function selectAnswer(idx, val) { answers[idx] = val; }
function navigate(dir) { const next = current + dir; if (next>=0 && next<TOTAL) renderQuestion(next); }

function openSubmitModal() {
  const unanswered = answers.filter(a => a === null || a === '').length;
  document.getElementById('modal-msg').textContent = unanswered > 0
    ? `You have ${unanswered} unanswered question${unanswered>1?'s':''}. Are you sure you want to submit?`
    : `You have answered all ${TOTAL} questions. Ready to submit?`;
  document.getElementById('submit-modal').classList.add('open');
}
function closeModal() { document.getElementById('submit-modal').classList.remove('open'); }

function submitExam() {
  clearInterval(timerInt);
  const elapsedSeconds = Math.floor(Date.now() / 1000) - START_TIME;
  document.getElementById('time-used-input').value = elapsedSeconds;

  const container = document.getElementById('hidden-answers');
  container.innerHTML = '';

  QUESTIONS.forEach((q, i) => {
    if (answers[i] !== null && answers[i] !== '') {
      const inp = document.createElement('input');
      inp.type = 'hidden';
      inp.name = `answers[${q.id}]`;
      inp.value = answers[i];
      container.appendChild(inp);
    }
  });

  document.getElementById('examForm').submit();
}

document.addEventListener('DOMContentLoaded', () => {
  if (!TOTAL) {
    document.getElementById('q-panel').innerHTML = '<div style="color:var(--gray-500);">No questions available.</div>';
    return;
  }
  renderQuestion(0);
  startTimer();
});
</script>
@endpush
@endsection

