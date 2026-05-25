@extends('layouts.dashboard')
@section('title', 'Draft Timetable')
@section('page-title', 'Draft🗓 Timetable')

@section('dashboard-content')

<style>
  /* ── Page ── */
.tt-page {
  max-width: 1100px;
  margin: 0 auto;
  padding: 0 0 40px;
}

/* ── Hero ── */
.tt-hero {
  display: flex; align-items: center; justify-content: space-between;
  gap: 24px; margin-bottom: 32px;
  background: linear-gradient(135deg, var(--green) 0%, #0E4D28 100%);
  border-radius: 20px; padding: 36px 40px;
  position: relative; overflow: hidden;
}
.tt-hero::before {
  content: '';
  position: absolute; inset: 0;
  background-image:
    linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
  background-size: 44px 44px;
  pointer-events: none;
}
.tt-hero-text { position: relative; z-index: 1; }
.tt-chip {
  display: inline-flex; align-items: center;
  background: rgba(255,255,255,.15); border: 1.5px solid rgba(255,255,255,.25);
  color: #fff; padding: 5px 14px; border-radius: 50px;
  font-size: .72rem; font-weight: 700; letter-spacing: .07em;
  text-transform: uppercase; margin-bottom: 14px;
}
.tt-hero-text h1 {
  font-family: 'Playfair Display', serif;
  font-size: 2rem; font-weight: 800; color: #fff;
  line-height: 1.2; margin-bottom: 12px;
}
.tt-hero-text h1 em { font-style: normal; color: rgba(255,255,255,.75); }
.tt-hero-text p {
  font-size: .88rem; color: rgba(255,255,255,.65);
  line-height: 1.6; max-width: 440px;
}
.tt-hero-badge {
  display: flex; flex-direction: column; align-items: center;
  gap: 10px; position: relative; z-index: 1; flex-shrink: 0;
}
.hero-badge-circle {
  width: 80px; height: 80px; border-radius: 50%;
  background: rgba(255,255,255,.15); border: 2px solid rgba(255,255,255,.25);
  display: grid; place-items: center; font-size: 2rem;
}
.hero-badge-label {
  font-size: .75rem; font-weight: 700; color: rgba(255,255,255,.8);
  text-transform: uppercase; letter-spacing: .08em;
}

/* ── Grid ── */
.tt-grid {
  display: grid;
  grid-template-columns: repeat(12, 1fr);
  gap: 16px;
}

/* ── Base Card ── */
.tt-card {
  background: var(--white); border: 1.5px solid var(--border);
  border-radius: 16px; padding: 22px 20px;
  box-shadow: 0 2px 10px var(--shadow);
}
.card-label {
  font-size: .7rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: .08em; color: var(--gray-400); margin-bottom: 16px;
}

/* ── Card Placements ── */
.card-identity  { grid-column: span 4; }
.card-subjects  { grid-column: span 5; }
.card-generate  { grid-column: span 3; }
.card-tips      { grid-column: span 4; }
.card-stats     { grid-column: span 4; }
.card-result    { grid-column: span 12; }

/* ── Generate Card (dark) ── */
.card-generate {
  background: linear-gradient(160deg, #0E4D28 0%, var(--green) 100%);
  border-color: transparent;
  display: flex; flex-direction: column; justify-content: center;
}
.card-generate .card-label { color: rgba(255,255,255,.5); }

/* ── Form Groups ── */
.fg { margin-bottom: 14px; }
.fg:last-child { margin-bottom: 0; }
.fg label {
  display: block; font-size: .74rem; font-weight: 600;
  color: var(--text-muted); text-transform: uppercase;
  letter-spacing: .05em; margin-bottom: 6px;
}
.fg input, .fg select {
  width: 100%; padding: 10px 13px;
  border: 1.5px solid var(--border); border-radius: 10px;
  font-size: .88rem; font-family: 'DM Sans', sans-serif;
  color: var(--text); outline: none; background: var(--white);
  transition: all .2s;
}
.fg input:focus, .fg select:focus {
  border-color: var(--green);
  box-shadow: 0 0 0 3px rgba(26,107,60,.1);
}

/* ── Subject Rows ── */
#subs { display: flex; flex-direction: column; gap: 8px; margin-bottom: 12px; }
.sub-row { display: flex; gap: 8px; align-items: center; }
.sub-row .si {
  flex: 1; padding: 10px 13px;
  border: 1.5px solid var(--border); border-radius: 10px;
  font-size: .85rem; font-family: 'DM Sans', sans-serif;
  color: var(--text); outline: none; transition: all .2s;
}
.sub-row .si:focus {
  border-color: var(--green);
  box-shadow: 0 0 0 3px rgba(26,107,60,.1);
}
.sub-row button {
  width: 30px; height: 30px; border-radius: 8px; flex-shrink: 0;
  background: #FEF2F2; border: 1.5px solid rgba(239,68,68,.2);
  color: #EF4444; font-size: .8rem; cursor: pointer;
  display: grid; place-items: center; transition: all .2s;
}
.sub-row button:hover { background: #EF4444; color: #fff; }

.btn-add-sub {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 8px 14px; border-radius: 8px;
  background: var(--green-light); border: 1.5px solid rgba(26,107,60,.2);
  color: var(--green); font-size: .8rem; font-weight: 700;
  font-family: 'DM Sans', sans-serif; cursor: pointer; transition: all .2s;
}
.btn-add-sub:hover { background: var(--green); color: #fff; }

.sub-counter {
  font-size: .72rem; color: var(--gray-400); margin-top: 8px; text-align: right;
}
#sub-count { font-weight: 700; color: var(--green); }

/* ── Generate Button ── */
.btn-gen {
  width: 100%; padding: 14px;
  background: #fff; color: var(--green);
  border: none; border-radius: 12px;
  font-family: 'DM Sans', sans-serif; font-size: .95rem; font-weight: 800;
  cursor: pointer; transition: all .2s; margin-bottom: 10px;
  box-shadow: 0 4px 16px rgba(0,0,0,.15);
}
.btn-gen:hover { background: var(--green-light); transform: translateY(-1px); }
.btn-gen:disabled { opacity: .6; cursor: not-allowed; transform: none; }
.gen-subtext {
  font-size: .7rem; color: rgba(255,255,255,.45);
  text-align: center; letter-spacing: .03em;
}

/* ── Tips Card ── */
.tip-item {
  display: flex; align-items: flex-start; gap: 10px;
  padding: 9px 0; border-bottom: 1.5px solid var(--border);
}
.tip-item:last-child { border: none; padding-bottom: 0; }
.tip-dot {
  width: 8px; height: 8px; border-radius: 50%;
  background: var(--green); flex-shrink: 0; margin-top: 5px;
}
.tip-item span { font-size: .82rem; color: var(--text-muted); line-height: 1.5; }

/* ── Stats Card ── */
.stat-row {
  display: grid; grid-template-columns: 1fr 1fr;
  gap: 12px;
}
.stat-item {
  background: var(--gray-50); border: 1.5px solid var(--border);
  border-radius: 10px; padding: 12px;
  display: flex; flex-direction: column; gap: 4px;
}
.stat-label {
  font-size: .68rem; font-weight: 600; text-transform: uppercase;
  letter-spacing: .06em; color: var(--gray-400);
}
.stat-val {
  font-family: 'Playfair Display', serif;
  font-size: 1.4rem; font-weight: 800; color: var(--green);
}

/* ── Result Card ── */
.result-empty {
  display: flex; flex-direction: column; align-items: center;
  justify-content: center; gap: 12px; padding: 50px 20px;
  text-align: center;
}
.result-empty .em-icon { font-size: 2.8rem; }
.result-empty p { font-size: .88rem; color: var(--text-muted); max-width: 360px; line-height: 1.6; }
.result-empty small { font-size: .75rem; color: var(--green); font-weight: 600; }

/* ── Timetable Table ── */
.tt-wrap { overflow-x: auto; margin-bottom: 20px; border-radius: 12px; border: 1.5px solid var(--border); }
.tt-tbl { width: 100%; border-collapse: collapse; min-width: 600px; }
.tt-tbl th {
  background: var(--green); color: #fff;
  padding: 11px 14px; font-size: .78rem; font-weight: 700;
  text-align: left; white-space: nowrap;
}
.tt-tbl th:first-child { border-radius: 10px 0 0 0; }
.tt-tbl th:last-child  { border-radius: 0 10px 0 0; }
.tt-tbl td {
  padding: 10px 14px; font-size: .82rem; color: var(--text-muted);
  border-bottom: 1.5px solid var(--border); vertical-align: middle;
}
.tt-tbl tr:last-child td { border-bottom: none; }
.tt-tbl tr:hover td { background: var(--green-pale); }
.tt-tbl td:first-child { font-weight: 700; color: var(--text); white-space: nowrap; }

/* ── Timetable Actions ── */
.tt-acts { display: flex; gap: 12px; }
.btn-print, .btn-clear {
  padding: 10px 20px; border-radius: 10px;
  font-family: 'DM Sans', sans-serif; font-size: .85rem;
  font-weight: 700; cursor: pointer; transition: all .2s; border: none;
}
.btn-print {
  background: var(--green); color: #fff;
  box-shadow: 0 4px 12px var(--shadow-md);
}
.btn-print:hover { background: var(--green-mid); transform: translateY(-1px); }
.btn-clear {
  background: var(--gray-100); color: var(--gray-600);
  border: 1.5px solid var(--border);
}
.btn-clear:hover { background: #FEF2F2; color: #EF4444; border-color: rgba(239,68,68,.2); }

/* ── Toast ── */
.tt-toast {
  position: fixed; bottom: 24px; right: 24px; z-index: 9999;
  padding: 12px 20px; border-radius: 12px; color: #fff;
  font-size: .85rem; font-weight: 600; font-family: 'DM Sans', sans-serif;
  box-shadow: 0 8px 24px rgba(0,0,0,.2);
  animation: slideUp .3s ease;
}
@keyframes slideUp {
  from { opacity: 0; transform: translateY(12px); }
  to   { opacity: 1; transform: none; }
}

/* ── Print ── */
@media print {
  .tt-hero, .card-identity, .card-subjects,
  .card-generate, .card-tips, .card-stats,
  .tt-acts { display: none !important; }
  .card-result { box-shadow: none; border: none; }
  .tt-tbl th { background: #1A6B3C !important; -webkit-print-color-adjust: exact; }
}

/* ── Mobile ── */
@media(max-width: 768px) {
  .tt-hero { flex-direction: column; padding: 28px 24px; }
  .tt-hero-text h1 { font-size: 1.5rem; }
  .tt-hero-badge { flex-direction: row; }
  .card-identity, .card-subjects, .card-generate,
  .card-tips, .card-stats, .card-result { grid-column: span 12; }
}
</style>
<div class="tt-page">

  <!-- HERO -->
  <div class="tt-hero">
    <div class="tt-hero-text">
      <div class="tt-chip">🎓 Free Tool</div>
      <h1>Instant <em> Draft Timetable</em><br>Generator</h1>
      <p>Drop in your courses, get a complete weekly study schedule in seconds. No sign-up, no payment — just your schedule.</p>
    </div>
    <div class="tt-hero-badge">
      <div class="hero-badge-circle">📅</div>
      <div class="hero-badge-label">100% Free</div>
    </div>
  </div>

  <!-- SCATTERED GRID -->
  <div class="tt-grid">

    <!-- CARD 1: Identity -->
    <div class="tt-card card-identity">
      <div class="card-label">Your Profile</div>
      <div class="fg">
        <label>Student Name</label>
        <input type="text" id="tt-name" placeholder="e.g. Adaeze Okoye" />
      </div>
      <div class="fg">
        <label>Academic Level</label>
        <select id="tt-level">
          <option>100 Level</option>
          <option>200 Level</option>
          <option>300 Level</option>
          <option>400 Level</option>
          <option>Postgraduate</option>
          <option>Masters</option>
        </select>
      </div>
    </div>

    <!-- CARD 2: Subjects -->
    <div class="tt-card card-subjects">
      <div class="card-label">Your Courses</div>
      <div id="subs">
        <div class="sub-row">
          <input type="text" placeholder="e.g. MTH 101 – Mathematics" class="si"
                 onkeyup="checkAndAddSubjectField(this)" />
        </div>
      </div>
      <button type="button" class="btn-add-sub" onclick="addSubjectField()">+ Add Course</button>
      <div class="sub-counter">
        <span id="sub-count">0</span> / 10 subjects added
      </div>
    </div>

    <!-- CARD 3: Generate (dark) -->
    <div class="tt-card card-generate">
      <div class="card-label">Ready?</div>
      <p style="color: rgba(255,255,255,0.55); font-size: 0.85rem; line-height: 1.6; margin-bottom: 20px;">
        Fill in your profile and courses, then hit generate. Your full week schedule will appear instantly.
      </p>
      <button class="btn-gen" onclick="generateTimetable()">
        🗓&nbsp; Generate
      </button>
      <div class="gen-subtext">Completely free — no card required</div>
    </div>

    <!-- CARD 4: Tips -->
    <div class="tt-card card-tips">
      <div class="card-label">Study Tips</div>
      <div class="tip-item"><div class="tip-dot"></div><span>Take a 5–10 min break every hour to maintain focus</span></div>
      <div class="tip-item"><div class="tip-dot"></div><span>Tackle hard subjects in the morning when your mind is sharpest</span></div>
      <div class="tip-item"><div class="tip-dot"></div><span>Review your notes before bed to strengthen memory</span></div>
      <div class="tip-item"><div class="tip-dot"></div><span>Keep a consistent sleep schedule for better retention</span></div>
    </div>

    <!-- CARD 5: Stats -->
    <div class="tt-card card-stats">
      <div class="card-label">Schedule Info</div>
      <div class="stat-row">
        <div class="stat-item">
          <span class="stat-label">Courses added</span>
          <span class="stat-val" id="stat-courses">0</span>
        </div>
        <div class="stat-item">
          <span class="stat-label">Study days</span>
          <span class="stat-val">6</span>
        </div>
        <div class="stat-item">
          <span class="stat-label">Daily slots</span>
          <span class="stat-val">5</span>
        </div>
        <div class="stat-item">
          <span class="stat-label">Total sessions</span>
          <span class="stat-val">30</span>
        </div>
      </div>
    </div>

    <!-- CARD 6: Result -->
    <div class="tt-card card-result" id="tt-result">
      <div class="card-label">Your Weekly Timetable</div>

      <div class="result-empty" id="tt-empty">
        <div class="em-icon">🗓️</div>
        <p>Fill in your profile and courses, then click generate to see your full weekly schedule.</p>
        <small>✨ It's completely free — no payment needed</small>
      </div>

      <div id="tt-out" style="display:none;">
        <div class="tt-wrap">
          <table class="tt-tbl" id="tt-tbl"></table>
        </div>
        <div class="tt-acts">
          <button class="btn-print" onclick="window.print()">🖨 Print Schedule</button>
          <button class="btn-clear" onclick="clearTimetable()">✕ Clear</button>
        </div>
      </div>
    </div>

  </div><!-- /.tt-grid -->
</div><!-- /.tt-page -->


<script>
  /* ── SUBJECT COUNTER ── */
  function updateCounts() {
    const inputs = document.querySelectorAll('#subs .si');
    const filled = Array.from(inputs).filter(i => i.value.trim() !== '').length;
    document.getElementById('sub-count').textContent = filled;
    document.getElementById('stat-courses').textContent = filled;
  }

  /* ── AUTO-ADD ON TYPING ── */
  function checkAndAddSubjectField(field) {
    updateCounts();
    const rows = document.querySelectorAll('#subs .sub-row');
    const isLast = Array.from(rows).indexOf(field.closest('.sub-row')) === rows.length - 1;
    if (isLast && field.value.trim() !== '' && rows.length < 10) addSubjectField();
  }

  /* ── ADD ROW ── */
  function addSubjectField() {
    const subs = document.getElementById('subs');
    const rows = subs.querySelectorAll('.sub-row');
    if (rows.length >= 10) { showToast('Maximum 10 subjects allowed', true); return; }

    const row = document.createElement('div');
    row.className = 'sub-row';

    const inp = document.createElement('input');
    inp.type = 'text';
    inp.placeholder = `e.g. Course ${rows.length + 1}`;
    inp.className = 'si';
    inp.onkeyup = function() { checkAndAddSubjectField(this); };
    inp.oninput = updateCounts;

    const btn = document.createElement('button');
    btn.type = 'button';
    btn.textContent = '✕';
    btn.onclick = function() { removeSubjectField(this); };

    row.appendChild(inp);
    row.appendChild(btn);
    subs.appendChild(row);
    inp.focus();
  }

  /* ── REMOVE ROW ── */
  function removeSubjectField(btn) {
    const row = btn.closest('.sub-row');
    const rows = document.querySelectorAll('#subs .sub-row');
    if (rows.length <= 1) {
      row.querySelector('input').value = '';
      showToast('You need at least one subject', true);
      updateCounts();
      return;
    }
    row.remove();
    updateCounts();
  }

  /* ── GENERATE ── */
  async function generateTimetable() {
    const name    = document.getElementById('tt-name').value.trim() || 'Student';
    const level   = document.getElementById('tt-level').value;
    const subjects = Array.from(document.querySelectorAll('#subs .si'))
                         .map(i => i.value.trim()).filter(Boolean);

    if (subjects.length === 0) { showToast('Please add at least one subject', true); return; }

    const btn = document.querySelector('.btn-gen');
    const orig = btn.innerHTML;
    btn.innerHTML = '⏳&nbsp; Generating…';
    btn.disabled = true;

    try {
      const res  = await fetch('{{ route("draft.generate") }}', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({ name, level, subjects })
      });

      const data = await res.json();

      if (data.success) {
        document.getElementById('tt-tbl').innerHTML   = data.timetable_html;
        document.getElementById('tt-out').style.display   = 'block';
        document.getElementById('tt-empty').style.display = 'none';

        let msg = `✅ ${data.total_found} course(s) scheduled`;
        if (data.total_missing > 0) msg += `, ${data.total_missing} not found`;
        showToast(msg);
        saveToLocalStorage(name, level, subjects);
      } else {
        showToast(data.message || 'Error generating timetable', true);
      }
    } catch (e) {
      console.error(e);
      showToast('Error generating timetable', true);
    } finally {
      btn.innerHTML = orig;
      btn.disabled  = false;
    }
  }

  /* ── CLEAR ── */
  function clearTimetable() {
    document.querySelectorAll('#subs .si').forEach((inp, i) => {
      if (i === 0) inp.value = '';
      else inp.closest('.sub-row')?.remove();
    });
    document.getElementById('tt-name').value          = '';
    document.getElementById('tt-out').style.display   = 'none';
    document.getElementById('tt-empty').style.display = 'flex';
    localStorage.removeItem('savedTimetable');
    updateCounts();
    showToast('Timetable cleared!');
  }

  /* ── STORAGE ── */
  function saveToLocalStorage(name, level, subjects) {
    localStorage.setItem('savedTimetable', JSON.stringify({ name, level, subjects, date: new Date().toISOString() }));
  }

  function loadFromLocalStorage() {
    try {
      const saved = JSON.parse(localStorage.getItem('savedTimetable'));
      if (!saved) return;
      if (!confirm('You have a saved timetable. Load it?')) return;

      document.getElementById('tt-name').value  = saved.name;
      document.getElementById('tt-level').value = saved.level;

      const subs = document.getElementById('subs');
      subs.querySelectorAll('.sub-row').forEach((r, i) => {
        if (i === 0) r.querySelector('input').value = saved.subjects[0] || '';
        else r.remove();
      });
      for (let i = 1; i < saved.subjects.length; i++) {
        addSubjectField();
        document.querySelectorAll('#subs .si')[i].value = saved.subjects[i];
      }
      updateCounts();
      showToast('Loaded saved timetable!');
      generateTimetable();
    } catch(e) {}
  }

  /* ── TOAST ── */
  function showToast(msg, isError = false) {
    document.querySelectorAll('.tt-toast').forEach(t => t.remove());
    const t = document.createElement('div');
    t.className = 'tt-toast';
    t.textContent = msg;
    t.style.background = isError ? '#EF4444' : '#1A6B3C';
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 3500);
  }

  /* ── INIT ── */
  document.addEventListener('DOMContentLoaded', () => {
    updateCounts();
    document.querySelectorAll('#subs .si').forEach(inp => {
      inp.addEventListener('input', updateCounts);
    });
    if (localStorage.getItem('savedTimetable')) {
      setTimeout(loadFromLocalStorage, 500);
    }
  });
</script>

@endsection