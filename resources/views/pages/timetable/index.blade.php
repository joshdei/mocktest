@extends('layouts.dashboard')
@section('title', 'Timetable')
@section('page-title', '🗓 Timetable')

@section('dashboard-content')

<style>
  @import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap');

  :root {
    --green-900: #0D3D22;
    --green-700: #1A6B3C;
    --green-500: #2D9B5A;
    --green-300: #6FCF97;
    --green-100: #D6F5E6;
    --green-50:  #F0FAF5;
    --ink:       #0F1A14;
    --ink-light: #4A5C52;
    --surface:   #FFFFFF;
    --surface-2: #F7FAF8;
    --border:    #DCE8E2;
    --radius-sm: 8px;
    --radius-md: 14px;
    --radius-lg: 22px;
    --radius-xl: 32px;
  }

  .tt-page {
    font-family: 'DM Sans', sans-serif;
    color: var(--ink);
    padding: 0 0 60px;
  }

  /* ── HERO HEADER ── */
  .tt-hero {
    position: relative;
    background: var(--green-900);
    border-radius: var(--radius-xl);
    padding: 52px 52px 0;
    margin-bottom: 40px;
    overflow: hidden;
    display: grid;
    grid-template-columns: 1fr auto;
    align-items: flex-end;
    gap: 32px;
  }

  .tt-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
      radial-gradient(ellipse 60% 80% at 80% 120%, rgba(45,155,90,0.35) 0%, transparent 60%),
      radial-gradient(ellipse 40% 60% at 10% -20%, rgba(111,207,151,0.15) 0%, transparent 50%);
    pointer-events: none;
  }

  .tt-hero-text { position: relative; padding-bottom: 48px; }

  .tt-chip {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(111,207,151,0.2);
    border: 1px solid rgba(111,207,151,0.4);
    color: var(--green-300);
    font-family: 'Syne', sans-serif;
    font-size: 0.7rem; font-weight: 600; letter-spacing: 0.12em;
    text-transform: uppercase;
    padding: 6px 14px; border-radius: 50px;
    margin-bottom: 20px;
  }

  .tt-hero h1 {
    font-family: 'Syne', sans-serif;
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 800; line-height: 1.1;
    color: #FFFFFF; margin: 0 0 16px;
  }
  .tt-hero h1 em { font-style: normal; color: var(--green-300); }

  .tt-hero p {
    font-size: 1rem; color: rgba(255,255,255,0.65);
    max-width: 440px; line-height: 1.6; margin: 0;
  }

  .tt-hero-badge {
    position: relative; align-self: flex-end; padding-bottom: 48px;
    display: flex; flex-direction: column; align-items: center; gap: 8px;
    text-align: center;
  }
  .hero-badge-circle {
    width: 96px; height: 96px; border-radius: 50%;
    background: linear-gradient(135deg, var(--green-500), var(--green-300));
    display: flex; align-items: center; justify-content: center;
    font-size: 2.4rem;
    box-shadow: 0 0 0 12px rgba(45,155,90,0.15), 0 0 0 24px rgba(45,155,90,0.07);
  }
  .hero-badge-label {
    font-family: 'Syne', sans-serif;
    font-size: 0.65rem; font-weight: 700; letter-spacing: 0.1em;
    text-transform: uppercase; color: var(--green-300);
  }

  /* ── SCATTERED GRID LAYOUT ── */
  .tt-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    grid-template-rows: auto;
    gap: 20px;
  }

  .tt-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 28px;
    transition: border-color 0.2s, transform 0.2s;
  }
  .tt-card:hover { border-color: var(--green-300); transform: translateY(-2px); }

  /* Card layout slots */
  .card-identity  { grid-column: 1; grid-row: 1; }
  .card-subjects  { grid-column: 2 / 4; grid-row: 1; }
  .card-generate  { grid-column: 1; grid-row: 2; background: var(--green-900); border-color: var(--green-700); }
  .card-tips      { grid-column: 2; grid-row: 2; background: var(--green-50); border-color: var(--green-100); }
  .card-stats     { grid-column: 3; grid-row: 2; }
  .card-result    { grid-column: 1 / 4; grid-row: 3; }

  /* ── CARD LABEL ── */
  .card-label {
    font-family: 'Syne', sans-serif;
    font-size: 0.65rem; font-weight: 700; letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--green-500); margin-bottom: 18px;
    display: flex; align-items: center; gap: 6px;
  }
  .card-label::before {
    content: ''; display: block;
    width: 18px; height: 2px;
    background: var(--green-500); border-radius: 2px;
  }

  /* ── FORM INPUTS ── */
  .fg { margin-bottom: 16px; }
  .fg label {
    display: block;
    font-size: 0.75rem; font-weight: 500; color: var(--ink-light);
    margin-bottom: 7px; letter-spacing: 0.02em;
  }
  .fg input[type="text"],
  .fg select {
    width: 100%; padding: 11px 14px;
    background: var(--surface-2); border: 1px solid var(--border);
    border-radius: var(--radius-sm); font-size: 0.9rem;
    color: var(--ink); font-family: 'DM Sans', sans-serif;
    outline: none; transition: border-color 0.2s, background 0.2s;
    box-sizing: border-box;
  }
  .fg input[type="text"]:focus,
  .fg select:focus {
    border-color: var(--green-500);
    background: var(--surface);
    box-shadow: 0 0 0 3px rgba(45,155,90,0.12);
  }

  /* ── SUBJECT ROWS ── */
  #subs { display: flex; flex-direction: column; gap: 8px; }
  .sub-row {
    display: flex; gap: 8px; align-items: center;
    animation: slideIn 0.2s ease;
  }
  @keyframes slideIn { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: translateY(0); } }

  .sub-row .si {
    flex: 1; padding: 10px 14px;
    background: var(--surface-2); border: 1px solid var(--border);
    border-radius: var(--radius-sm); font-size: 0.88rem;
    color: var(--ink); font-family: 'DM Sans', sans-serif;
    outline: none; transition: border-color 0.2s;
  }
  .sub-row .si:focus {
    border-color: var(--green-500);
    box-shadow: 0 0 0 3px rgba(45,155,90,0.12);
    background: var(--surface);
  }

  .sub-row button {
    width: 34px; height: 34px; border-radius: 50%;
    background: #FEE2E2; border: none; color: #DC2626;
    font-size: 0.9rem; cursor: pointer; flex-shrink: 0;
    transition: background 0.15s;
  }
  .sub-row button:hover { background: #FECACA; }

  .btn-add-sub {
    display: inline-flex; align-items: center; gap: 6px;
    margin-top: 10px;
    background: var(--green-50); border: 1.5px dashed var(--green-300);
    color: var(--green-700); border-radius: var(--radius-sm);
    padding: 9px 16px; font-size: 0.82rem; font-weight: 500;
    cursor: pointer; transition: background 0.15s; font-family: 'DM Sans', sans-serif;
    width: 100%; justify-content: center;
  }
  .btn-add-sub:hover { background: var(--green-100); }

  .sub-counter {
    font-size: 0.72rem; color: var(--ink-light);
    text-align: right; margin-top: 8px;
  }
  .sub-counter span {
    font-weight: 600;
    color: var(--green-700);
    font-family: 'Syne', sans-serif;
  }

  /* ── GENERATE CARD ── */
  .card-generate .card-label { color: var(--green-300); }
  .card-generate .card-label::before { background: var(--green-300); }

  .btn-gen {
    width: 100%; padding: 16px;
    background: linear-gradient(135deg, var(--green-500), #1db954);
    border: none; border-radius: var(--radius-md);
    color: white; font-family: 'Syne', sans-serif;
    font-size: 1rem; font-weight: 700;
    cursor: pointer; letter-spacing: 0.01em;
    transition: transform 0.2s, box-shadow 0.2s;
    box-shadow: 0 4px 20px rgba(45,155,90,0.4);
    margin-top: 8px;
  }
  .btn-gen:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(45,155,90,0.5); }
  .btn-gen:active { transform: scale(0.98); }
  .btn-gen:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

  .gen-subtext {
    color: rgba(255,255,255,0.45);
    font-size: 0.72rem; text-align: center; margin-top: 12px;
  }

  /* ── TIPS CARD ── */
  .tip-item {
    display: flex; align-items: flex-start; gap: 10px;
    margin-bottom: 14px; font-size: 0.82rem; color: var(--ink-light);
    line-height: 1.5;
  }
  .tip-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: var(--green-500); flex-shrink: 0; margin-top: 5px;
  }

  /* ── STATS CARD ── */
  .stat-row { display: flex; flex-direction: column; gap: 14px; }
  .stat-item {
    display: flex; justify-content: space-between; align-items: center;
    padding-bottom: 14px; border-bottom: 1px solid var(--border);
  }
  .stat-item:last-child { border-bottom: none; padding-bottom: 0; }
  .stat-label { font-size: 0.78rem; color: var(--ink-light); }
  .stat-val {
    font-family: 'Syne', sans-serif;
    font-size: 1.1rem; font-weight: 700; color: var(--green-700);
  }

  /* ── RESULT CARD ── */
  .result-empty {
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 56px 24px; text-align: center; gap: 12px;
  }
  .result-empty .em-icon { font-size: 3rem; }
  .result-empty p { color: var(--ink-light); font-size: 0.95rem; max-width: 340px; }
  .result-empty small { color: #A0ADB8; font-size: 0.8rem; }

  .tt-wrap {
    overflow-x: auto;
    border-radius: var(--radius-md);
    border: 1px solid var(--border);
    width: 100%;
  }
  .tt-tbl {
    width: 100%; border-collapse: collapse;
    font-size: 0.82rem; min-width: 600px;
    table-layout: auto;
  }
  /* Make sure the last column (exam type / venue) doesn't clip */
  .tt-tbl td:last-child,
  .tt-tbl th:last-child {
    white-space: normal !important;
    word-break: break-word;
  }
  .tt-tbl thead tr:first-child th {
    background: var(--green-900); color: white;
    font-family: 'Syne', sans-serif;
    font-size: 0.85rem; font-weight: 700;
    padding: 16px; text-align: center;
  }
  .tt-tbl thead tr:nth-child(2) th {
    background: var(--green-50); color: var(--green-700);
    font-family: 'Syne', sans-serif; font-size: 0.72rem;
    font-weight: 700; letter-spacing: 0.08em;
    text-transform: uppercase; padding: 12px 10px;
    border-bottom: 2px solid var(--green-100);
  }
  .tt-tbl tbody td {
    padding: 11px 10px; vertical-align: middle;
    border-bottom: 1px solid var(--border);
    transition: background 0.15s;
  }
  .tt-tbl tbody tr:hover td { background: var(--green-50) !important; }
  .tt-tbl tbody td:first-child {
    font-family: 'Syne', sans-serif; font-weight: 700;
    font-size: 0.72rem; color: var(--green-700);
    background: var(--surface-2); white-space: nowrap;
    letter-spacing: 0.04em; text-transform: uppercase;
  }

  .subject-chip {
    display: inline-block;
    background: var(--green-50); color: var(--green-700);
    border: 1px solid var(--green-100);
    border-radius: 6px; padding: 4px 8px;
    font-size: 0.75rem; font-weight: 500;
    line-height: 1.3;
  }
  .subject-chip small { display: block; color: var(--ink-light); font-size: 0.68rem; font-weight: 400; }

  .lunch-cell { text-align: center; color: #D97706; font-weight: 500; font-size: 0.8rem; }

  .tt-acts {
    display: flex; gap: 10px; margin-top: 18px; justify-content: flex-end;
  }
  .btn-print {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 10px 22px; border-radius: var(--radius-sm);
    background: var(--green-700); color: white;
    border: none; cursor: pointer; font-family: 'DM Sans', sans-serif;
    font-size: 0.85rem; font-weight: 500;
    transition: background 0.15s;
  }
  .btn-print:hover { background: var(--green-900); }

  .btn-clear {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 10px 22px; border-radius: var(--radius-sm);
    background: transparent; color: var(--ink-light);
    border: 1px solid var(--border); cursor: pointer;
    font-family: 'DM Sans', sans-serif; font-size: 0.85rem;
    transition: border-color 0.15s, color 0.15s;
  }
  .btn-clear:hover { border-color: #EF4444; color: #EF4444; }

  /* ── COURSE NOT FOUND CHIP ── */
  .chip-missing {
    display: inline-block;
    background: #FEF3C7; color: #92400E;
    border: 1px solid #FDE68A;
    border-radius: 6px; padding: 4px 8px;
    font-size: 0.75rem; font-weight: 500;
  }

  /* ── TOAST ── */
  .tt-toast {
    position: fixed; bottom: 24px; right: 24px;
    padding: 12px 22px; border-radius: var(--radius-md);
    font-family: 'DM Sans', sans-serif; font-weight: 500;
    font-size: 0.88rem; color: white;
    box-shadow: 0 8px 24px rgba(0,0,0,0.2);
    z-index: 9999;
    animation: toastIn 0.25s ease;
  }
  @keyframes toastIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }

  /* ── PRINT ── */
  @media print {
    @page {
      size: A4 landscape;
      margin: 12mm 10mm;
    }

    * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }

    body, .tt-page { margin: 0 !important; padding: 0 !important; }

    .tt-hero,
    .card-identity,
    .card-subjects,
    .card-generate,
    .card-tips,
    .card-stats { display: none !important; }

    .tt-grid {
      display: block !important;
    }

    .card-result {
      border: none !important;
      padding: 0 !important;
      box-shadow: none !important;
      transform: none !important;
      width: 100% !important;
    }

    .card-label { display: none !important; }

    .tt-wrap {
      overflow: visible !important;
      border: none !important;
      width: 100% !important;
    }

    .tt-tbl {
      width: 100% !important;
      min-width: unset !important;
      table-layout: fixed !important;
      font-size: 0.75rem !important;
      border-collapse: collapse !important;
    }

    .tt-tbl thead tr:first-child th {
      padding: 10px 8px !important;
      font-size: 0.85rem !important;
    }

    .tt-tbl thead tr:nth-child(2) th,
    .tt-tbl tbody td {
      padding: 8px 6px !important;
      word-break: break-word !important;
      overflow-wrap: break-word !important;
    }

    .tt-acts { display: none !important; }

    .result-empty { display: none !important; }
  }

  /* ── RESPONSIVE ── */
  @media (max-width: 900px) {
    .tt-grid { grid-template-columns: 1fr 1fr; }
    .card-identity  { grid-column: 1; grid-row: 1; }
    .card-subjects  { grid-column: 2; grid-row: 1; }
    .card-generate  { grid-column: 1; grid-row: 2; }
    .card-tips      { grid-column: 2; grid-row: 2; }
    .card-stats     { grid-column: 1 / 3; grid-row: 3; }
    .card-result    { grid-column: 1 / 3; grid-row: 4; }
    .tt-hero { grid-template-columns: 1fr; }
    .tt-hero-badge { display: none; }
    .tt-hero { padding: 40px 32px 40px; }
  }
  @media (max-width: 600px) {
    .tt-grid { grid-template-columns: 1fr; }
    .card-identity, .card-subjects, .card-generate,
    .card-tips, .card-stats, .card-result { grid-column: 1; }
    .card-identity  { grid-row: 1; }
    .card-subjects  { grid-row: 2; }
    .card-generate  { grid-row: 3; }
    .card-tips      { grid-row: 4; }
    .card-stats     { grid-row: 5; }
    .card-result    { grid-row: 6; }
    .tt-hero { padding: 28px 22px 28px; border-radius: var(--radius-lg); }
  }
</style>

<div class="tt-page">

  <!-- HERO -->
  <div class="tt-hero">
    <div class="tt-hero-text">
      <div class="tt-chip">🎓 Free Tool</div>
      <h1>Instant <em>Timetable</em><br>Generator</h1>
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
        🗓&nbsp; Generate My Timetable
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
      const res  = await fetch('{{ route("timetable.generate") }}', {
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