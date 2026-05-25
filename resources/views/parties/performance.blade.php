<!-- ============================================================
     Performance Dashboard — Topic Scores · Top Ranks · Heatmap
     Drop <style> into your main CSS, HTML into Blade, <script> at bottom
     ============================================================ -->

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  .db {
    font-family: 'Space Grotesk', system-ui, sans-serif;
    padding: 1.5rem 0;
  }

  /* ── HEADER ── */
  .db-head {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    margin-bottom: 1.25rem;
    flex-wrap: wrap;
    gap: 8px;
  }

  .db-title {
    font-family: 'Instrument Serif', Georgia, serif;
    font-size: 26px;
    font-style: italic;
    color: #0f172a;
    line-height: 1.1;
  }

  .db-title span {
    font-style: normal;
    font-family: 'Space Grotesk', system-ui, sans-serif;
    font-size: 11px;
    font-weight: 500;
    color: #94a3b8;
    display: block;
    letter-spacing: .6px;
    text-transform: uppercase;
    margin-bottom: 2px;
  }

  /* ── PERIOD BUTTONS ── */
  .period-btns { display: flex; gap: 4px; }

  .pb {
    font-size: 11px;
    font-weight: 500;
    padding: 5px 12px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: transparent;
    color: #64748b;
    cursor: pointer;
    transition: all .15s;
    font-family: 'Space Grotesk', system-ui, sans-serif;
  }

  .pb:hover { border-color: #cbd5e1; color: #0f172a; }
  .pb.on    { background: #0f172a; color: #ffffff; border-color: #0f172a; }

  /* ── KPI ROW ── */
  .kpi-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 10px;
    margin-bottom: 1.25rem;
  }

  .kpi {
    background: #f8fafc;
    border-radius: 10px;
    padding: 14px 16px;
  }

  .kpi-val {
    font-size: 26px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1;
  }

  .kpi-lbl {
    font-size: 11px;
    color: #94a3b8;
    margin-top: 4px;
    font-weight: 400;
  }

  .kpi-delta {
    font-size: 11px;
    font-weight: 500;
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 3px;
  }

  .kpi-delta i { font-size: 12px; }
  .kpi-delta.up { color: #0F6E56; }
  .kpi-delta.dn { color: #A32D2D; }

  /* ── MAIN GRID ── */
  .main-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    margin-bottom: 14px;
  }

  @media (max-width: 560px) {
    .main-grid { grid-template-columns: 1fr; }
  }

  /* ── CARDS ── */
  .card {
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 14px;
    padding: 1.1rem 1.2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
  }

  .ch {
    font-size: 11px;
    font-weight: 500;
    color: #94a3b8;
    letter-spacing: .4px;
    text-transform: uppercase;
    margin-bottom: .9rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .ch i { font-size: 15px; color: #cbd5e1; }

  /* ── TOPIC SCORES ── */
  .topic-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
    border-bottom: 1px solid #f8fafc;
  }

  .topic-row:last-child { border-bottom: none; }

  .t-rank {
    font-size: 11px;
    font-weight: 700;
    color: #cbd5e1;
    width: 16px;
    flex-shrink: 0;
    text-align: center;
  }

  .t-name {
    font-size: 13px;
    font-weight: 500;
    color: #1e293b;
    flex: 1;
  }

  .t-bar-wrap {
    width: 72px;
    height: 4px;
    background: #f1f5f9;
    border-radius: 999px;
    overflow: hidden;
    flex-shrink: 0;
  }

  .t-bar {
    height: 100%;
    border-radius: 999px;
    width: 0%;
    transition: width .9s ease;
  }

  .t-pct {
    font-size: 12px;
    font-weight: 700;
    width: 34px;
    text-align: right;
    flex-shrink: 0;
  }

  .t-badge {
    font-size: 10px;
    font-weight: 500;
    padding: 2px 7px;
    border-radius: 999px;
    flex-shrink: 0;
  }

  /* ── LEADERBOARD ── */
  .ldr-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
    border-bottom: 1px solid #f8fafc;
  }

  .ldr-row:last-child { border-bottom: none; }

  .ldr-pos {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: 700;
    flex-shrink: 0;
  }

  .ldr-pos.gold   { background: #FAEEDA; color: #633806; }
  .ldr-pos.silver { background: #F1EFE8; color: #444441; }
  .ldr-pos.bronze { background: #FAECE7; color: #712B13; }
  .ldr-pos.plain  { background: #f8fafc; color: #94a3b8; }

  .ldr-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 700;
    flex-shrink: 0;
  }

  .ldr-name {
    font-size: 13px;
    font-weight: 500;
    color: #1e293b;
  }

  .ldr-sub {
    font-size: 11px;
    color: #94a3b8;
  }

  .ldr-you {
    font-size: 10px;
    padding: 2px 7px;
    border-radius: 999px;
    background: #E6F1FB;
    color: #0C447C;
    margin-left: 5px;
    font-weight: 500;
  }

  /* ── HEATMAP ── */
  .heat-label-row {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 4px;
    margin-bottom: 4px;
  }

  .heat-lbl {
    font-size: 10px;
    color: #94a3b8;
    text-align: center;
    font-weight: 400;
  }

  .heat-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 4px;
    margin-top: .25rem;
  }

  .hcell {
    height: 24px;
    border-radius: 4px;
    cursor: default;
    transition: opacity .15s;
  }

  .hcell:hover { opacity: .7; }

  .heat-legend {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 10px;
    font-size: 11px;
    color: #94a3b8;
  }

  .heat-legend-cells { display: flex; gap: 3px; }

  .hlc { width: 14px; height: 14px; border-radius: 3px; }

  .ch-sub {
    font-size: 11px;
    font-weight: 400;
    color: #94a3b8;
    text-transform: none;
    letter-spacing: 0;
  }
</style>


<!-- ── HTML (Blade) ── -->
<section class="db">

  <!-- Header -->
  <div class="db-head">
    <div class="db-title">
      <span>Dashboard</span>
      Your performance
    </div>
    <div class="period-btns">
      <button class="pb on" data-p="week">Week</button>
      <button class="pb" data-p="month">Month</button>
      <button class="pb" data-p="all">All time</button>
    </div>
  </div>

  <!-- KPI chips -->
  <div class="kpi-row">
    <div class="kpi">
      <div class="kpi-val">{{ $performanceData['kpi']['accuracy']['value'] }}%</div>
      <div class="kpi-lbl">Accuracy</div>
      <div class="kpi-delta {{ $performanceData['kpi']['accuracy']['trend'] }}">
        <i class="ti ti-trending-{{ $performanceData['kpi']['accuracy']['trend'] === 'up' ? 'up' : 'down' }}"></i> 
        {{ $performanceData['kpi']['accuracy']['trend'] === 'up' ? '+' : '' }}{{ $performanceData['kpi']['accuracy']['delta'] }}% vs last
      </div>
    </div>
    <div class="kpi">
      <div class="kpi-val">{{ $performanceData['kpi']['questions']['value'] }}</div>
      <div class="kpi-lbl">Questions</div>
      <div class="kpi-delta {{ $performanceData['kpi']['questions']['trend'] }}">
        <i class="ti ti-trending-{{ $performanceData['kpi']['questions']['trend'] === 'up' ? 'up' : 'down' }}"></i> 
        {{ $performanceData['kpi']['questions']['trend'] === 'up' ? '+' : '' }}{{ $performanceData['kpi']['questions']['delta'] }} vs last
      </div>
    </div>
    <div class="kpi">
      <div class="kpi-val">{{ $performanceData['kpi']['hours']['value'] }}h</div>
      <div class="kpi-lbl">Hours studied</div>
      <div class="kpi-delta {{ $performanceData['kpi']['hours']['trend'] }}">
        <i class="ti ti-trending-{{ $performanceData['kpi']['hours']['trend'] === 'up' ? 'up' : 'down' }}"></i> 
        {{ $performanceData['kpi']['hours']['trend'] === 'up' ? '+' : '' }}{{ $performanceData['kpi']['hours']['delta'] }}h vs last
      </div>
    </div>
    <div class="kpi">
      <div class="kpi-val">{{ $performanceData['kpi']['tests']['value'] }}</div>
      <div class="kpi-lbl">Tests taken</div>
      <div class="kpi-delta {{ $performanceData['kpi']['tests']['trend'] }}">
        <i class="ti ti-trending-{{ $performanceData['kpi']['tests']['trend'] === 'up' ? 'up' : 'down' }}"></i> 
        {{ $performanceData['kpi']['tests']['trend'] === 'up' ? '+' : '' }}{{ $performanceData['kpi']['tests']['delta'] }} vs last
      </div>
    </div>
  </div>

  <!-- Topic scores + Leaderboard -->
  <div class="main-grid">

    <div class="card">
      <div class="ch">
        Topic scores
        <i class="ti ti-adjustments-horizontal" aria-hidden="true"></i>
      </div>
      <div id="topicRows"></div>
    </div>

    <div class="card">
      <div class="ch">
        Top ranks
        <i class="ti ti-trophy" aria-hidden="true"></i>
      </div>
      <div id="ldrRows"></div>
    </div>

  </div>

  <!-- Weekly activity heatmap -->
  <div class="card">
    <div class="ch">
      Weekly activity
      <span class="ch-sub">questions answered per day</span>
    </div>
    <div class="heat-label-row">
      <div class="heat-lbl">Mon</div>
      <div class="heat-lbl">Tue</div>
      <div class="heat-lbl">Wed</div>
      <div class="heat-lbl">Thu</div>
      <div class="heat-lbl">Fri</div>
      <div class="heat-lbl">Sat</div>
      <div class="heat-lbl">Sun</div>
    </div>
    <div class="heat-grid" id="heatGrid"></div>
    <div class="heat-legend">
      Less
      <div class="heat-legend-cells">
        <div class="hlc" style="background:#f1f5f9;"></div>
        <div class="hlc" style="background:#9FE1CB;"></div>
        <div class="hlc" style="background:#5DCAA5;"></div>
        <div class="hlc" style="background:#1D9E75;"></div>
        <div class="hlc" style="background:#085041;"></div>
      </div>
      More
    </div>
  </div>

</section>


<script>

  const topics = @json($performanceData['topics']);
  const topicColor = { strong: "#1D9E75", avg: "#BA7517", focus: "#A32D2D" };
  const topicBadge = {
    strong: { bg: "#E1F5EE", color: "#085041", label: "Strong"  },
    avg:    { bg: "#FAEEDA", color: "#633806", label: "Average" },
    focus:  { bg: "#FCEBEB", color: "#791F1F", label: "Focus"   },
  };

  function renderTopics() {
    const el = document.getElementById("topicRows");
    const sorted = [...topics].sort((a, b) => b.pct - a.pct);
    
    if (sorted.length === 0) {
      el.innerHTML = '<div style="text-align:center;color:#94a3b8;padding:20px;">No test data yet</div>';
      return;
    }
    
    el.innerHTML = sorted.map((t, i) => `
      <div class="topic-row">
        <span class="t-rank">${i + 1}</span>
        <span class="t-name">${t.name}</span>
        <div class="t-bar-wrap">
          <div class="t-bar" data-w="${t.pct}" style="background:${topicColor[t.status]};"></div>
        </div>
        <span class="t-pct" style="color:${topicColor[t.status]};">${t.pct}%</span>
        <span class="t-badge" style="background:${topicBadge[t.status].bg};color:${topicBadge[t.status].color};">
          ${topicBadge[t.status].label}
        </span>
      </div>`).join("");
    setTimeout(() => {
      document.querySelectorAll(".t-bar").forEach(b => { b.style.width = b.dataset.w + "%"; });
    }, 80);
  }

  const leaders = @json($performanceData['leaderboard']);

  const posClass = ["gold", "silver", "bronze", "plain", "plain"];

  function renderLeaders() {
    const el = document.getElementById("ldrRows");
    
    if (leaders.length === 0) {
      el.innerHTML = '<div style="text-align:center;color:#94a3b8;padding:20px;">No rankings yet</div>';
      return;
    }
    
    el.innerHTML = leaders.map((l, i) => `
      <div class="ldr-row">
        <div class="ldr-pos ${posClass[i]}">${i + 1}</div>
        <div class="ldr-avatar" style="background:#f1f5f9;color:#64748b;">${l.initials}</div>
        <div style="flex:1;min-width:0;">
          <div style="display:flex;align-items:center;">
            <span class="ldr-name">${l.name}</span>
            ${l.you ? '<span class="ldr-you">you</span>' : ''}
          </div>
          <div class="ldr-sub">${l.score.toLocaleString()} pts</div>
        </div>
        <div style="font-size:13px;font-weight:700;color:#94a3b8;">#${i + 1}</div>
      </div>`).join("");
  }


  const heatData = @json($performanceData['heatmap']);

  function heatColor(v) {
    if (v === 0) return "#f1f5f9";
    if (v <= 5)  return "#9FE1CB";
    if (v <= 12) return "#5DCAA5";
    if (v <= 20) return "#1D9E75";
    return "#085041";
  }

  function renderHeat() {
    const el = document.getElementById("heatGrid");
    el.innerHTML = heatData.map(v =>
      `<div class="hcell" style="background:${heatColor(v)};" title="${v} questions"></div>`
    ).join("");
  }

  /* ── PERIOD TOGGLE ── */
  document.querySelectorAll(".pb").forEach(b => {
    b.addEventListener("click", () => {
      document.querySelectorAll(".pb").forEach(x => x.classList.remove("on"));
      b.classList.add("on");
      /* Swap in real data per period here if needed */
    });
  });

  renderTopics();
  renderLeaders();
  renderHeat();
</script>