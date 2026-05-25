@extends('layouts.dashboard')

@section('title')
@section('page-title')
@section('dashboard-content')

<style>
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

/* ── Back Link ── */
.back-link {
  display: inline-flex; align-items: center; gap: 6px;
  font-size: .82rem; font-weight: 600; color: var(--green);
  text-decoration: none; margin-bottom: 20px;
  transition: opacity .2s;
}
.back-link:hover { opacity: .7; }

/* ── Header ── */
.tx-header {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 20px;
}
.tx-header h2 {
  font-family: 'Playfair Display', serif;
  font-size: 1.3rem; font-weight: 800; color: var(--text); margin-bottom: 4px;
}
.tx-count {
  font-size: .78rem; color: var(--text-muted); font-weight: 500;
}

/* ── Table Wrap ── */
.tx-table-wrap {
  background: var(--white); border: 1.5px solid var(--border);
  border-radius: 16px; overflow: hidden;
  margin-bottom: 20px;
  box-shadow: 0 4px 20px var(--shadow);
}

.tx-table {
  width: 100%; border-collapse: collapse;
}

.tx-table thead tr {
  background: var(--gray-50);
  border-bottom: 1.5px solid var(--border);
}
.tx-table thead th {
  padding: 13px 16px;
  font-size: .7rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: .07em; color: var(--gray-400);
  text-align: left; white-space: nowrap;
}

.tx-table tbody tr {
  border-bottom: 1.5px solid var(--border);
  transition: background .15s;
}
.tx-table tbody tr:last-child { border: none; }
.tx-table tbody tr:hover { background: var(--gray-50); }

.tx-table tbody td {
  padding: 13px 16px;
  font-size: .84rem; color: var(--text-muted); vertical-align: middle;
}

/* ── Amount ── */
.tx-amt {
  font-weight: 800 !important; font-size: .9rem !important;
  white-space: nowrap;
}
.tx-amt.credit { color: var(--green) !important; }
.tx-amt.debit  { color: #EF4444 !important; }

/* ── Type Badge ── */
.tx-badge {
  display: inline-block; padding: 3px 10px; border-radius: 50px;
  font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em;
}
.badge-credit { background: var(--green-light); color: var(--green); border: 1px solid rgba(26,107,60,.2); }
.badge-debit  { background: #FEF2F2; color: #EF4444; border: 1px solid rgba(239,68,68,.2); }

/* ── Status Badge ── */
.tx-status {
  display: inline-block; padding: 3px 10px; border-radius: 50px;
  font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em;
}
.status-success { background: var(--green-light); color: var(--green); border: 1px solid rgba(26,107,60,.2); }
.status-pending { background: #FEF3C7; color: #D97706; border: 1px solid rgba(217,119,6,.2); }
.status-failed  { background: #FEF2F2; color: #EF4444; border: 1px solid rgba(239,68,68,.2); }

/* ── Pagination ── */
.pagination-wrap {
  display: flex; justify-content: center; margin-bottom: 24px;
}
.pagination-wrap .pagination {
  display: flex; gap: 6px; list-style: none; padding: 0; margin: 0;
}
.pagination-wrap .page-item .page-link {
  display: flex; align-items: center; justify-content: center;
  width: 36px; height: 36px; border-radius: 8px;
  font-size: .82rem; font-weight: 600; color: var(--text);
  border: 1.5px solid var(--border); background: var(--white);
  text-decoration: none; transition: all .2s;
}
.pagination-wrap .page-item .page-link:hover {
  border-color: var(--green); color: var(--green);
  background: var(--green-pale);
}
.pagination-wrap .page-item.active .page-link {
  background: var(--green); color: #fff; border-color: var(--green);
}
.pagination-wrap .page-item.disabled .page-link {
  opacity: .4; pointer-events: none;
}

/* ── Empty State ── */
.tx-empty {
  background: var(--white); border: 1.5px solid var(--border);
  border-radius: 16px; padding: 60px 20px;
  display: flex; flex-direction: column;
  align-items: center; gap: 12px; text-align: center;
}
.tx-empty .em-icon { font-size: 2.8rem; }
.tx-empty p { font-size: .88rem; color: var(--text-muted); }
.tx-empty a { color: var(--green); font-weight: 600; text-decoration: none; }
.tx-empty a:hover { text-decoration: underline; }

/* ── Mobile ── */
@media(max-width: 640px) {
  .tx-table thead th:nth-child(1),
  .tx-table tbody td:nth-child(1) { display: none; } /* hide reference col */
  .tx-table thead th,
  .tx-table tbody td { padding: 11px 10px; }
}

.back-link { font-size: clamp(.76rem, 3vw, .82rem); }
.tx-header h2 { font-size: clamp(1.06rem, 5vw, 1.3rem); }
.tx-count { font-size: clamp(.72rem, 3vw, .78rem); }
.tx-table thead th { font-size: clamp(.64rem, 2.6vw, .7rem); }
.tx-table tbody td { font-size: clamp(.76rem, 3.1vw, .84rem); }
.tx-table tbody td[style] { font-size: clamp(.7rem, 2.8vw, .78rem) !important; }
.tx-table span[style] { font-size: clamp(.66rem, 2.8vw, .72rem) !important; }
.tx-amt { font-size: clamp(.8rem, 3.2vw, .9rem) !important; }
.tx-badge,
.tx-status { font-size: clamp(.62rem, 2.5vw, .68rem); }
.pagination-wrap .page-item .page-link { font-size: clamp(.76rem, 3vw, .82rem); }
.tx-empty .em-icon { font-size: clamp(2.1rem, 10vw, 2.8rem); }
.tx-empty p { font-size: clamp(.8rem, 3.2vw, .88rem); }

.tx-header,
.tx-table-wrap,
.tx-table,
.tx-table tbody,
.tx-table tbody tr,
.tx-table tbody td,
.pagination-wrap,
.tx-empty {
  min-width: 0;
}

.tx-header {
  align-items: flex-start;
  flex-direction: column;
  gap: 10px;
}

.tx-table-wrap {
  width: 100%;
  max-width: 100%;
  overflow: hidden;
}

.tx-table,
.tx-table thead,
.tx-table tbody,
.tx-table tr,
.tx-table td {
  display: block;
  width: 100%;
}

.tx-table thead {
  display: none;
}

.tx-table tbody tr {
  padding: 12px 0;
}

.tx-table tbody td,
.tx-table tbody td:nth-child(1) {
  display: grid;
  grid-template-columns: minmax(92px, 34%) minmax(0, 1fr);
  gap: 10px;
  align-items: start;
  padding: 8px 14px;
  overflow-wrap: anywhere;
}

.tx-table tbody td::before {
  color: var(--gray-400);
  content: '';
  font-size: clamp(.62rem, 2.5vw, .68rem);
  font-weight: 700;
  letter-spacing: .05em;
  text-transform: uppercase;
}

.tx-table tbody td:nth-child(1)::before { content: 'Reference'; }
.tx-table tbody td:nth-child(2)::before { content: 'Date'; }
.tx-table tbody td:nth-child(3)::before { content: 'Description'; }
.tx-table tbody td:nth-child(4)::before { content: 'Type'; }
.tx-table tbody td:nth-child(5)::before { content: 'Amount'; }
.tx-table tbody td:nth-child(6)::before { content: 'Status'; }

.pagination-wrap {
  max-width: 100%;
  overflow: hidden;
}

.pagination-wrap .pagination {
  flex-wrap: wrap;
  justify-content: center;
}

.pagination-wrap .page-item .page-link {
  width: 34px;
  height: 34px;
}

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

@media (min-width: 480px) {
  .review-ticker-wrap {
    display: block;
  }
}

@media (min-width: 768px) {
  .tx-header {
    align-items: center;
    flex-direction: row;
  }

  .tx-table {
    display: table;
    table-layout: fixed;
  }

  .tx-table thead {
    display: table-header-group;
  }

  .tx-table tbody {
    display: table-row-group;
  }

  .tx-table tr {
    display: table-row;
  }

  .tx-table thead th,
  .tx-table tbody td,
  .tx-table tbody td:nth-child(1) {
    display: table-cell;
    width: auto;
  }

  .tx-table tbody td::before {
    content: none !important;
  }

  .tx-table thead th:nth-child(1),
  .tx-table tbody td:nth-child(1) {
    display: table-cell;
  }

  .pagination-wrap .page-item .page-link {
    width: 36px;
    height: 36px;
  }

  .welcome-strip {
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
  }
}

@media (min-width: 1024px) {
  .quick-row {
    grid-template-columns: repeat(4, minmax(0, 1fr));
  }

  .calendar-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}
</style>

<a href="{{ route('wallet') }}" class="back-link">← Back to Wallet</a>

<div class="tx-header">
  <div>
    <h2>📋 Transaction</h2>
    <div class="tx-count">{{ $transactions->total() }} transaction{{ $transactions->total() == 1 ? '' : 's' }} found</div>
  </div>
</div>

@if($transactions->count())
<div class="tx-table-wrap">
  <table class="tx-table">
    <thead>
      <tr>
        <th>Reference</th>
        <th>Date</th>
        <th>Description</th>
        <th>Type</th>
        <th>Amount</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach($transactions as $tx)
      <tr>
        <td style="font-weight:600;color:var(--text);font-size:.78rem;">{{ $tx->reference }}</td>
        <td>
          {{ $tx->created_at->format('M d, Y') }}<br>
          <span style="font-size:.72rem;color:var(--gray-400);">{{ $tx->created_at->format('h:i A') }}</span>
        </td>
        <td>{{ $tx->description ?? 'Wallet transaction' }}</td>
        <td><span class="tx-badge badge-{{ $tx->type }}">{{ $tx->type }}</span></td>
        <td class="tx-amt {{ $tx->type }}">{{ $tx->type == 'credit' ? '+' : '-' }}₦{{ number_format($tx->amount) }}</td>
        <td><span class="tx-status status-{{ $tx->status }}">{{ ucfirst($tx->status) }}</span></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div class="pagination-wrap">
  {{ $transactions->links('pagination::bootstrap-5') }}
</div>

@else
<div class="tx-empty">
  <span class="em-icon">📭</span>
  <p>No transactions yet. <a href="{{ route('wallet') }}">Fund your wallet</a> to get started.</p>
</div>
@endif

@endsection
