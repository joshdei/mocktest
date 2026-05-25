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

.wallet-balance-card {
  background: linear-gradient(120deg, var(--green) 0%, #0E4D28 100%);
  border-radius: var(--r-lg);
  padding: 32px 30px;
  color: #fff;
  margin-bottom: 24px;
  position: relative;
  overflow: hidden;
}
.wallet-balance-card::before {
  content: '';
  position: absolute; inset: 0;
  background-image: linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px);
  background-size: 44px 44px;
}
.wb-content { position: relative; z-index: 1; }
.wb-label { font-size: .88rem; color: rgba(255,255,255,.7); margin-bottom: 6px; }
.wb-amount { font-family: 'Playfair Display', serif; font-size: 2.4rem; font-weight: 800; color: #fff; }
.wb-actions { display: flex; gap: 12px; margin-top: 20px; }
.wb-btn {
  padding: 10px 22px; border-radius: 10px; font-size: .85rem;
  font-weight: 700; font-family: 'DM Sans', sans-serif; cursor: pointer;
  border: none; transition: all .2s;
}
.wb-btn.primary { background: #fff; color: var(--green); }
.wb-btn.primary:hover { background: var(--green-light); }
.wb-btn.secondary { background: rgba(255,255,255,.15); color: #fff; border: 1.5px solid rgba(255,255,255,.3); }
.wb-btn.secondary:hover { background: rgba(255,255,255,.25); }

.fund-form {
  background: var(--white); border: 1.5px solid var(--border);
  border-radius: var(--r-lg); padding: 24px;
  margin-bottom: 24px; display: none;
}
.fund-form.show { display: block; animation: fadeIn .3s ease; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }

.form-group { margin-bottom: 16px; }
.form-label { display: block; font-size: .78rem; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; text-transform: uppercase; letter-spacing: .04em; }
.form-input {
  width: 100%; padding: 11px 14px; border: 1.5px solid var(--border);
  border-radius: 10px; font-size: .9rem; font-family: 'DM Sans', sans-serif;
  color: var(--text); outline: none; transition: all .2s;
}
.form-input:focus { border-color: var(--green); box-shadow: 0 0 0 3px rgba(26,107,60,.12); }
.form-input, .form-select { font-size: 1rem; }
.form-select {
  width: 100%; padding: 11px 14px; border: 1.5px solid var(--border);
  border-radius: 10px; font-size: .9rem; font-family: 'DM Sans', sans-serif;
  color: var(--text); outline: none; transition: all .2s; background: var(--white);
  -webkit-appearance: none; appearance: none;
}
.form-select:focus { border-color: var(--green); box-shadow: 0 0 0 3px rgba(26,107,60,.12); }
.btn-submit {
  width: 100%; padding: 12px; background: var(--green); color: #fff;
  border: none; border-radius: 10px; font-size: .9rem; font-weight: 700;
  font-family: 'DM Sans', sans-serif; cursor: pointer; transition: all .2s;
  box-shadow: 0 4px 16px rgba(26,107,60,.3);
}
.btn-submit:hover { background: var(--green-mid); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(26,107,60,.4); }

.alert-success {
  background: var(--green-light); color: var(--green);
  border: 1.5px solid rgba(26,107,60,.2); padding: 12px 16px;
  border-radius: 10px; margin-bottom: 18px; font-size: .87rem; font-weight: 600;
}
.alert-error {
  background: #FEF2F2; color: #EF4444;
  border: 1.5px solid rgba(239,68,68,.2); padding: 12px 16px;
  border-radius: 10px; margin-bottom: 18px; font-size: .87rem; font-weight: 600;
}

.wallet-info-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }
.wi-card {
  background: var(--white); border: 1.5px solid var(--border);
  border-radius: var(--r); padding: 18px 20px; text-align: center;
}
.wi-icon { font-size: 1.6rem; margin-bottom: 10px; }
.wi-val { font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 800; color: var(--text); }
.wi-label { font-size: .75rem; color: var(--text-muted); margin-top: 4px; }

.recent-tx {
  background: var(--white); border: 1.5px solid var(--border);
  border-radius: var(--r-lg); padding: 22px;
}
.rtx-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
.rtx-title { font-family: 'Playfair Display', serif; font-size: 1rem; font-weight: 700; color: var(--text); }
.rtx-link { font-size: .78rem; color: var(--green); font-weight: 600; text-decoration: none; }
.rtx-link:hover { text-decoration: underline; }

.tx-item { display: flex; align-items: center; gap: 12px; padding: 11px 0; border-bottom: 1.5px solid var(--border); }
.tx-item:last-child { border: none; padding-bottom: 0; }
.tx-icon { width: 38px; height: 38px; border-radius: 10px; display: grid; place-items: center; font-size: 1.1rem; flex-shrink: 0; }
.tx-info { flex: 1; }
.tx-desc { font-size: .85rem; font-weight: 600; color: var(--text); }
.tx-ref { font-size: .72rem; color: var(--text-muted); margin-top: 2px; }
.tx-amt { font-size: .9rem; font-weight: 700; margin-right: 12px; }
.tx-amt.credit { color: var(--green); }
.tx-amt.debit { color: #EF4444; }
.tx-status {
  font-size: .65rem; font-weight: 700; padding: 3px 9px;
  border-radius: 20px; white-space: nowrap;
}
.status-success { background: var(--green-light); color: var(--green); border: 1px solid rgba(26,107,60,.2); }
.status-pending { background: #FEF3C7; color: #D97706; border: 1px solid rgba(217,119,6,.2); }
.status-failed { background: #FEF2F2; color: #EF4444; border: 1px solid rgba(239,68,68,.2); }

.empty-tx {
  display: flex; flex-direction: column; align-items: center;
  padding: 40px 20px; gap: 10px; color: var(--gray-400);
}
.empty-tx .em-icon { font-size: 2.5rem; }
.empty-tx p { font-size: .88rem; color: var(--gray-500); }

@media(max-width:640px){
  .wallet-info-grid { grid-template-columns: 1fr; }
}

/* ── Cashback Card ── */
.cashback-card {
  background: linear-gradient(135deg, #FAF5FF 0%, #EDE9FE 100%);
  border: 1.5px solid rgba(124,58,237,.2);
  border-radius: var(--r-lg);
  padding: 24px;
  margin-bottom: 24px;
}
.cb-content { position: relative; }

.cb-head {
  display: flex; align-items: center;
  justify-content: space-between; margin-bottom: 20px;
}
.cb-title {
  font-family: 'Playfair Display', serif;
  font-size: 1.05rem; font-weight: 800; color: #4C1D95;
}
.cb-badge {
  font-size: .7rem; font-weight: 700; letter-spacing: .05em;
  text-transform: uppercase; padding: 5px 12px; border-radius: 50px;
  background: rgba(124,58,237,.12); color: #7C3AED;
  border: 1.5px solid rgba(124,58,237,.2);
}

/* Stats row */
.cb-stats {
  display: grid; grid-template-columns: repeat(3,1fr);
  gap: 12px; margin-bottom: 16px;
}
.cb-stat {
  background: rgba(255,255,255,.7); border: 1.5px solid rgba(124,58,237,.15);
  border-radius: 12px; padding: 14px 12px; text-align: center;
}
.cb-stat-val {
  font-family: 'Playfair Display', serif;
  font-size: 1.3rem; font-weight: 800; color: #7C3AED; line-height: 1;
}
.cb-stat-label {
  font-size: .7rem; color: #6D28D9; font-weight: 600;
  text-transform: uppercase; letter-spacing: .05em; margin-top: 5px;
}

/* Next badge */
.cb-next-badge {
  background: rgba(124,58,237,.08); border: 1.5px solid rgba(124,58,237,.15);
  border-radius: 10px; padding: 10px 14px;
  font-size: .8rem; font-weight: 600; color: #6D28D9;
  margin-bottom: 20px; text-align: center;
}

.cb-divider {
  border: none; border-top: 1.5px solid rgba(124,58,237,.15);
  margin-bottom: 16px;
}

.cb-history-title {
  font-size: .75rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: .07em; color: #7C3AED; margin-bottom: 12px;
}

/* History items */
.cb-item {
  display: flex; align-items: center; gap: 12px;
  padding: 11px 0; border-bottom: 1.5px solid rgba(124,58,237,.1);
}
.cb-item:last-child { border: none; padding-bottom: 0; }

.cb-item-icon {
  width: 38px; height: 38px; border-radius: 10px;
  background: rgba(124,58,237,.1); display: grid;
  place-items: center; font-size: 1.1rem; flex-shrink: 0;
}
.cb-item-info { flex: 1; }
.cb-item-desc { font-size: .85rem; font-weight: 600; color: #4C1D95; }
.cb-item-date { font-size: .72rem; color: #7C3AED; margin-top: 2px; opacity: .7; }
.cb-item-amt {
  font-size: .9rem; font-weight: 800; color: #7C3AED;
  white-space: nowrap;
}

.cb-empty {
  text-align: center; padding: 24px 16px;
  font-size: .85rem; color: #7C3AED; opacity: .7; line-height: 1.6;
}

@media(max-width:640px){
  .cb-stats { grid-template-columns: 1fr 1fr; }
  .cb-head { flex-direction: column; align-items: flex-start; gap: 10px; }
}

.wallet-balance-card,
.fund-form,
.wallet-info-grid,
.cashback-card,
.recent-tx {
  width: 100%;
  max-width: 100%;
}

.wb-content,
.wi-card,
.recent-tx,
.tx-info,
.cashback-card,
.cb-content,
.cb-stat,
.cb-item-info {
  min-width: 0;
}

.wb-label { font-size: clamp(.78rem, 3.2vw, .88rem); }
.wb-amount {
  font-size: clamp(1.8rem, 10vw, 2.4rem);
  overflow-wrap: anywhere;
}
.wb-actions {
  flex-direction: column;
  width: 100%;
}
.wb-btn {
  width: 100%;
  max-width: 100%;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  box-sizing: border-box;
  text-align: center;
  font-size: clamp(.8rem, 3.2vw, .95rem) !important;
}

.form-title {
  font-family: 'Playfair Display', serif;
  font-size: clamp(.98rem, 4vw, 1.1rem);
  font-weight: 700;
  color: var(--text);
  margin-bottom: 16px;
}
.fund-form h3 {
  font-size: clamp(.98rem, 4vw, 1.1rem) !important;
}
.form-label { font-size: clamp(.7rem, 2.8vw, .78rem); }
.form-input,
.form-select {
  width: 100%;
  font-size: clamp(.88rem, 3.4vw, 1rem);
}
.btn-submit { font-size: clamp(.86rem, 3.3vw, .9rem); }
.alert-success,
.alert-error { font-size: clamp(.78rem, 3.2vw, .87rem); }

.wallet-info-grid {
  grid-template-columns: minmax(0, 1fr) !important;
  gap: 14px;
}
.wi-icon { font-size: clamp(1.28rem, 6vw, 1.6rem); }
.wi-val {
  font-size: clamp(1.05rem, 5.5vw, 1.4rem);
  overflow-wrap: anywhere;
}
.wi-label { font-size: clamp(.68rem, 2.8vw, .75rem); }

.rtx-head {
  flex-direction: column;
  align-items: flex-start;
  gap: 8px;
}
.rtx-title { font-size: clamp(.9rem, 3.6vw, 1rem); }
.rtx-link { font-size: clamp(.72rem, 2.8vw, .78rem); }

.tx-item {
  display: grid;
  grid-template-columns: 38px minmax(0, 1fr);
  gap: 10px 12px;
  align-items: center;
  padding: 12px 0;
}
.tx-icon { font-size: clamp(.96rem, 4vw, 1.1rem); }
.tx-desc {
  font-size: clamp(.78rem, 3.2vw, .85rem);
  overflow-wrap: anywhere;
}
.tx-ref {
  font-size: clamp(.66rem, 2.8vw, .72rem);
  overflow-wrap: anywhere;
}
.tx-amt {
  grid-column: 2;
  font-size: clamp(.78rem, 3.2vw, .9rem);
  margin-right: 0;
}
.tx-status {
  grid-column: 2;
  justify-self: start;
  font-size: clamp(.6rem, 2.4vw, .65rem);
}
.empty-tx .em-icon { font-size: clamp(2rem, 9vw, 2.5rem); }
.empty-tx p {
  font-size: clamp(.8rem, 3.2vw, .88rem);
  text-align: center;
}

.cashback-card {
  overflow: hidden;
}
.cb-head {
  flex-direction: column;
  align-items: flex-start;
  gap: 10px;
}
.cb-title { font-size: clamp(.94rem, 3.8vw, 1.05rem); }
.cb-badge { font-size: clamp(.64rem, 2.7vw, .7rem); }
.cb-stats {
  grid-template-columns: minmax(0, 1fr);
}
.cb-stat-val {
  font-size: clamp(1.02rem, 5vw, 1.3rem);
  overflow-wrap: anywhere;
}
.cb-stat-label { font-size: clamp(.64rem, 2.7vw, .7rem); }
.cb-next-badge { font-size: clamp(.74rem, 3vw, .8rem); }
.cb-history-title { font-size: clamp(.68rem, 2.8vw, .75rem); }
.cb-item {
  display: grid;
  grid-template-columns: 38px minmax(0, 1fr);
  gap: 10px 12px;
}
.cb-item-icon { font-size: clamp(.96rem, 4vw, 1.1rem); }
.cb-item-desc {
  font-size: clamp(.78rem, 3.2vw, .85rem);
  overflow-wrap: anywhere;
}
.cb-item-date {
  font-size: clamp(.66rem, 2.8vw, .72rem);
  overflow-wrap: anywhere;
}
.cb-item-amt {
  grid-column: 2;
  font-size: clamp(.8rem, 3.2vw, .9rem);
}
.cb-empty { font-size: clamp(.78rem, 3.2vw, .85rem); }

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
  .review-ticker-wrap { display: block; }
  .wallet-info-grid,
  .cb-stats { grid-template-columns: repeat(2, minmax(0, 1fr)) !important; }
  .rtx-head,
  .cb-head { flex-direction: row; align-items: center; }
}

@media (min-width: 768px) {
  .wb-actions {
    flex-direction: row;
    width: auto;
  }
  .wb-btn { max-width: max-content; }
  .wallet-info-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    gap: 16px;
  }
  .tx-item {
    display: flex;
    gap: 12px;
  }
  .tx-info { flex: 1; }
  .tx-amt {
    grid-column: auto;
    margin-right: 12px;
  }
  .tx-status { grid-column: auto; }
  .cb-stats { grid-template-columns: repeat(3, minmax(0, 1fr)) !important; }
  .cb-item {
    display: flex;
    gap: 12px;
  }
  .cb-item-info { flex: 1; }
  .cb-item-amt { grid-column: auto; }
  .welcome-strip {
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
  }
}

@media (min-width: 1024px) {
  .wallet-info-grid,
  .quick-row {
    grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
  }
  .calendar-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}
</style>



<!-- Balance Card -->
<div class="wallet-balance-card">
  <div class="wb-content">
    <div class="wb-label">Available Balance</div>
    <div class="wb-amount">₦{{ number_format($studentWallet->balance ?? 0, 2) }}</div>
    <div class="wb-actions">
      <button class="wb-btn primary" onclick="toggleFundForm()" style="box-shadow: 0 4px 12px rgba(255,255,255,.3); font-size: .95rem; padding: 12px 26px;">💳 Fund Wallet</button>
      <a href="{{ route('transactions') }}" class="wb-btn secondary" style="text-decoration:none;">📜 View History</a>
    </div>
  </div>
</div>

<!-- Fund Form -->
<div class="fund-form" id="fundForm">
  <h3 style="font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:var(--text);margin-bottom:16px;">💳 Fund Your Wallet</h3>
  <form action="{{ route('wallet.fund') }}" method="POST" id="fundWalletForm">
    @csrf
    <div class="form-group">
      <label class="form-label">Amount (₦)</label>
      <input type="number" name="amount" min="100" step="50" class="form-input" placeholder="e.g. 2000" required id="amount">
    </div>
    <div class="form-group">
      <label class="form-label">Payment Method</label>
      <select name="payment_method" class="form-select" required id="paymentMethod">
        <option value="">Select method</option>
        <option value="card">💳 Debit / Credit Card</option>
        {{-- <option value="bank">🏦 Bank Transfer</option>
        <option value="ussd">📱 USSD</option> --}}
      </select>
    </div>
    <button type="submit" class="btn-submit" id="submitBtn">Proceed to Pay</button>
  </form>
</div>

<!-- Info Grid -->
<div class="wallet-info-grid" style="grid-template-columns: repeat(4, 1fr);">
  <div class="wi-card">
    <div class="wi-icon">💰</div>
    <div class="wi-val">₦{{ number_format($studentWallet->balance ?? 0) }}</div>
    <div class="wi-label">Current Balance</div>
  </div>
  <div class="wi-card">
    <div class="wi-icon">📥</div>
    <div class="wi-val">₦{{ number_format($totalFunded ?? 0) }}</div>
    <div class="wi-label">Total Funded</div>
  </div>
  <div class="wi-card">
    <div class="wi-icon">📤</div>
    <div class="wi-val">₦{{ number_format($totalSpent ?? 0) }}</div>
    <div class="wi-label">Total Spent</div>
  </div>
  <div class="wi-card" style="border-color: rgba(124,58,237,.3); background: #FAF5FF;">
    <div class="wi-icon">🎁</div>
    <div class="wi-val" style="color: #7C3AED;">₦{{ number_format($totalCashback ?? 0) }}</div>
    <div class="wi-label">Total Cashback</div>
  </div>
</div>
{{-- Cashback Card --}}
@if(isset($totalCashback))
<div class="cashback-card">
  <div class="cb-content">
    <div class="cb-head">
      <div class="cb-title">🎁 Daily Cashback Rewards</div>
      @php
        $todayCashback = $cashbackHistory->first() && 
            \Carbon\Carbon::parse($cashbackHistory->first()->cashback_date)->isToday();
      @endphp
      <span class="cb-badge">
        {{ $todayCashback ? '✅ Claimed Today' : '⏳ Login Tomorrow' }}
      </span>
    </div>

    {{-- Stats --}}
    <div class="cb-stats">
      <div class="cb-stat">
        <div class="cb-stat-val">₦{{ number_format($totalCashback, 2) }}</div>
        <div class="cb-stat-label">Total Earned</div>
      </div>
      <div class="cb-stat">
        <div class="cb-stat-val">{{ $cashbackHistory->count() }}</div>
        <div class="cb-stat-label">Days Claimed</div>
      </div>
      <div class="cb-stat">
        <div class="cb-stat-val">₦5.00</div>
        <div class="cb-stat-label">Per Day</div>
      </div>
    </div>

    {{-- Next cashback info --}}
    <div class="cb-next-badge">
      @if($todayCashback)
        🕐 Next cashback available tomorrow
      @else
        🎯 Login today to claim your ₦5 cashback!
      @endif
    </div>

    <hr class="cb-divider">

    {{-- Cashback history --}}
    <div class="cb-history-title">Recent Cashback History</div>

    @forelse($cashbackHistory as $cb)
    <div class="cb-item">
      <div class="cb-item-icon">🎁</div>
      <div class="cb-item-info">
        <div class="cb-item-desc">
          Daily Login Cashback
          @if($cb->plan)
            · {{ $cb->plan->name }}
          @endif
        </div>
        <div class="cb-item-date">
          {{ \Carbon\Carbon::parse($cb->cashback_date)->format('M d, Y · g:i A') }}
          · {{ \Carbon\Carbon::parse($cb->cashback_date)->diffForHumans() }}
        </div>
      </div>
      <div class="cb-item-amt">+₦{{ number_format($cb->amount, 2) }}</div>
    </div>
    @empty
    <div class="cb-empty">
      😔 No cashback yet. Subscribe to our highest plan and login daily to earn ₦5 every day!
    </div>
    @endforelse

  </div>
</div>
@endif
<!-- Recent Transactions -->
<div class="recent-tx">
  <div class="rtx-head">
    <div class="rtx-title">📋 Recent Transactions</div>
    <a href="{{ route('transactions') }}" class="rtx-link">View All →</a>
  </div>
  
  @forelse($recentTx as $tx)
  <div class="tx-item">
    <div class="tx-icon" style="background:{{ $tx->type == 'credit' ? 'var(--green-light)' : '#FEF2F2' }}">
      {{ $tx->type == 'credit' ? '💰' : '💸' }}
    </div>
    <div class="tx-info">
      <div class="tx-desc">{{ $tx->description ?? 'Wallet transaction' }}</div>
      <div class="tx-ref">{{ $tx->reference }} · {{ $tx->created_at->diffForHumans() }}</div>
    </div>
    <div class="tx-amt {{ $tx->type == 'credit' ? 'credit' : 'debit' }}">
      {{ $tx->type == 'credit' ? '+' : '-' }}₦{{ number_format($tx->amount) }}
    </div>
    <span class="tx-status status-{{ $tx->status }}">{{ ucfirst($tx->status) }}</span>
  </div>
  @empty
  <div class="empty-tx">
    <span class="em-icon">📭</span>
    <p>No transactions yet. Fund your wallet to get started.</p>
  </div>
  @endforelse
</div>

<script>
function toggleFundForm() {
  const form = document.getElementById('fundForm');
  form.classList.toggle('show');
  
  if (form.classList.contains('show')) {
    form.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }
}

// Handle form submission with proper loading state
document.getElementById('fundWalletForm')?.addEventListener('submit', function(e) {
  const submitBtn = document.getElementById('submitBtn');
  const amount = document.getElementById('amount').value;
  const paymentMethod = document.getElementById('paymentMethod').value;
  
  // Validate
  if (!amount || amount < 100) {
    e.preventDefault();
    alert('Please enter a valid amount (minimum ₦100)');
    return false;
  }
  
  if (!paymentMethod) {
    e.preventDefault();
    alert('Please select a payment method');
    return false;
  }
  
  // Show loading state
  submitBtn.disabled = true;
  submitBtn.textContent = 'Redirecting to Paystack...';
  
  // Allow form to submit normally
  return true;
});
</script>
@endsection
