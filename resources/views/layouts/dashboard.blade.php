@extends('layouts.app')
<!-- Default Statcounter code for mock
https://mock.psalmedu.com -->
<script type="text/javascript">
var sc_project=13240696; 
var sc_invisible=1; 
var sc_security="ff720f52"; 
</script>
<script type="text/javascript"
src="https://www.statcounter.com/counter/counter.js"
async></script>
<noscript><div class="statcounter"><a title="Web Analytics
Made Easy - Statcounter" href="https://statcounter.com/"
target="_blank"><img class="statcounter"
src="https://c.statcounter.com/13240696/0/ff720f52/1/"
alt="Web Analytics Made Easy - Statcounter"
referrerPolicy="no-referrer-when-downgrade"></a></div></noscript>
<!-- End of Statcounter Code -->
@section('content')
@php
// Get user's active subscription and plan colors
$userPlanColor = null;
$userSubscription = \App\Models\UserSubscription::where('user_id', auth()->id())
    ->where('status', 'active')
    ->where(function($query) {
        $query->whereNull('expiry_date')
            ->orWhere('expiry_date', '>=', \Carbon\Carbon::now());
    })
    ->first();

if ($userSubscription) {
    $userPlanColor = \App\Models\MockpriceColor::where('plan_id', $userSubscription->plan_id)->first();
}

// Default colors
$themeColor = '#1A6B3C'; // Default green
$bgColor = '#E8F5EE'; // Default light green

if ($userPlanColor) {
    if ($userPlanColor->bg_theme) {
        $themeColor = $userPlanColor->bg_theme;
    }
    if ($userPlanColor->bg_color) {
        $bgColor = $userPlanColor->bg_color;
    }
}
@endphp
<style>
:root {
  --green:       {{ $themeColor }};
  --green-mid:   {{ $themeColor }};
  --green-light: {{ $bgColor }};
  --green-pale:  {{ $bgColor }};
  --lime:        #4CAF7D;
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
  --gray-900:    #111827;
  --text:        #1C2B1E;
  --text-muted:  #5A6B5E;
  --border:      #DDE8E1;
  --shadow:      rgba({{ ltrim($themeColor, '#') }},.08);
  --shadow-md:   rgba({{ ltrim($themeColor, '#') }},.14);
  --nav-h:       64px;
  --sidebar-w:   240px;
  --r:           12px;
  --r-lg:        18px;
}

/* ── LAYOUT ── */
.app { display: flex; min-height: 100vh; }

/* ── SIDEBAR ── */
.sidebar {
  width: var(--sidebar-w);
  background: var(--white);
  border-right: 1.5px solid var(--border);
  display: flex; flex-direction: column;
  position: fixed;
  top: 0; left: 0; bottom: 0;
  z-index: 100;
  transition: transform .3s;
  overflow-y: auto;
  overflow-x: hidden;
}
.sb-logo {
  height: var(--nav-h);
  display: flex; align-items: center; gap: 11px;
  padding: 0 20px;
  border-bottom: 1.5px solid var(--border);
  flex-shrink: 0;
}
.logo-mark {
  width: 36px; height: 36px; background: var(--green);
  border-radius: 9px; display: grid; place-items: center;
  font-family: 'Playfair Display', serif; font-size: 18px;
  font-weight: 800; color: #fff;
  box-shadow: 0 3px 10px var(--shadow-md);
}
.logo-name { font-family: 'Playfair Display', serif; font-size: 1.05rem; font-weight: 700; color: var(--text); line-height: 1.1; }
.logo-name span { color: var(--green); }
.logo-sub { font-size: .58rem; color: var(--text-muted); font-weight: 500; letter-spacing: .08em; text-transform: uppercase; margin-top: 1px; }

.sb-section { padding: 20px 12px 0; }
.sb-label { font-size: .66rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: var(--gray-400); padding: 0 8px; margin-bottom: 6px; }
.sb-item {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 10px; border-radius: 10px;
  font-size: .865rem; font-weight: 500; color: var(--gray-600);
  text-decoration: none; cursor: pointer;
  transition: all .18s; margin-bottom: 2px;
  border: none; background: none; width: 100%; text-align: left;
  font-family: 'DM Sans', sans-serif;
}
.sb-item:hover { background: var(--green-pale); color: var(--green); }
.sb-item.active { background: var(--green-light); color: var(--green); font-weight: 700; }
.sb-item .si { font-size: 1.05rem; width: 20px; text-align: center; flex-shrink: 0; }
.sb-badge {
  margin-left: auto; background: var(--green); color: #fff;
  font-size: .62rem; font-weight: 700; padding: 2px 7px;
  border-radius: 20px; letter-spacing: .02em;
}
.sb-badge.amber { background: #D97706; }

.sb-bottom {
  margin-top: auto;
  padding: 16px 12px;
  border-top: 1.5px solid var(--border);
}
.sb-user {
  display: flex; align-items: center; gap: 11px;
  padding: 10px 10px; border-radius: 10px; cursor: pointer;
  transition: background .18s;
}
.sb-user:hover { background: var(--green-pale); }
.user-av {
  width: 36px; height: 36px; border-radius: 50%;
  background: var(--green); display: grid; place-items: center;
  font-size: .8rem; font-weight: 700; color: #fff; flex-shrink: 0;
}
.user-name { font-size: .85rem; font-weight: 700; color: var(--text); }
.user-meta { font-size: .72rem; color: var(--text-muted); }

/* ── MAIN ── */
.main {
  margin-left: var(--sidebar-w);
  flex: 1; display: flex; flex-direction: column;
  min-width: 0;
  padding-top: var(--nav-h);
}

/* ── TOPBAR ── */
.topbar {
  height: var(--nav-h);
  background: var(--white);
  border-bottom: 1.5px solid var(--border);
  display: flex; align-items: center;
  padding: 0 28px; gap: 14px;
  position: fixed; top: 0; left: var(--sidebar-w); right: 0; z-index: 120;
}
.page-title { font-family: 'Playfair Display', serif; font-size: 1.25rem; font-weight: 700; color: var(--text); flex: 1; }
.search-wrap {
  display: flex; align-items: center; gap: 8px;
  background: var(--gray-50); border: 1.5px solid var(--border);
  border-radius: 10px; padding: 8px 14px;
}
.search-wrap input {
  background: none; border: none; outline: none;
  font-family: 'DM Sans', sans-serif; font-size: .85rem;
  color: var(--text); width: 210px;
}
.search-wrap input::placeholder { color: var(--gray-400); }
.search-wrap .si { color: var(--gray-400); font-size: .95rem; }
.topbar-actions { display: flex; gap: 10px; align-items: center; }
.icon-btn {
  width: 38px; height: 38px; border-radius: 10px;
  background: var(--gray-50); border: 1.5px solid var(--border);
  display: grid; place-items: center; cursor: pointer; font-size: 1rem;
  position: relative; transition: all .18s;
}
.icon-btn:hover { background: var(--green-light); border-color: rgba(26,107,60,.25); }
.notif-dot {
  position: absolute; top: 7px; right: 7px;
  width: 7px; height: 7px; border-radius: 50%;
  background: #EF4444; border: 2px solid var(--white);
}
.hamburger { display: none; flex-direction: column; gap: 5px; cursor: pointer; background: none; border: none; }
.hamburger span { width: 22px; height: 2px; background: var(--gray-700); border-radius: 2px; display: block; }

/* ── CONTENT ── */
.content { padding: 28px; flex: 1; }

/* ── RESPONSIVE ── */
@media(max-width:960px){
  :root { --sidebar-w: 0px; }
  .sidebar { transform: translateX(-240px); width: 240px; }
  .sidebar.open { transform: translateX(0); }
  .main { margin-left: 0; }
  .hamburger { display: flex; }
}
@media(max-width:560px){
  .content { padding: 16px; }
}
</style>

<link rel="stylesheet" href="{{ asset('css/setup.css') }}">
<link rel="stylesheet" href="{{ asset('css/dashboardstyle.css') }}">
<link rel="stylesheet" href="{{ asset('css/dashboardstyle2.css') }}">
<link rel="stylesheet" href="{{ asset('css/plan.css') }}">
<link rel="stylesheet" href="{{ asset('css/setup.css') }}">
<div class="app">
{{-- @include('parties.sidebar') --}}
  <!-- ── SIDEBAR ── -->
 <aside class="sidebar" id="sidebar">
    <div class="sb-logo">
        @php
        use Illuminate\Support\Str;
        @endphp
 
        <div class="logo-mark">
            {{ Str::upper(Str::substr(config('app.name'), 0, 1)) }}
        </div>
        <div>
            <div class="logo-name"><span>{{ config('app.name') }}</span></div>
            <div class="logo-sub">Student Portal</div>
        </div>
    </div>

    <div class="sb-section">
        <div class="sb-label">Main</div>
        <a href="{{ route('dashboard') }}" class="sb-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt si"></i> Dashboard
        </a>
        <a href="{{ route('mock.index') }}" class="sb-item {{ request()->routeIs('mock.index') ? 'active' : '' }}">
            <i class="fas fa-file-alt si"></i> Exam
        </a>
        <a href="{{ route('mock.results') }}" class="sb-item {{ request()->routeIs('mock.results') ? 'active' : '' }}">
            <i class="fas fa-chart-line si"></i> Result
        </a>
    </div>

    <div class="sb-section" style="margin-top:10px;">
        <div class="sb-label">Wallet</div>
        <a href="{{ route('wallet') }}" class="sb-item {{ request()->routeIs('wallet') ? 'active' : '' }}">
            <i class="fas fa-wallet si"></i> Wallet
        </a>
        <a href="{{ route('transactions') }}" class="sb-item {{ request()->routeIs('transactions') ? 'active' : '' }}">
            <i class="fas fa-exchange-alt si"></i> Transactions
        </a>
    </div>

    {{-- <div class="sb-section" style="margin-top:10px;">
        <div class="sb-label">Store</div>
        <a href="{{ route('store.summaries') }}" class="sb-item {{ request()->routeIs('store.summaries') ? 'active' : '' }}">
            <i class="fas fa-book-open si"></i> Summary
        </a>
        <a href="{{ route('store.past-questions') }}" class="sb-item {{ request()->routeIs('store.past-questions') ? 'active' : '' }}">
            <i class="fas fa-database si"></i> Past Questions
        </a>
        <a href="{{ route('store.materials') }}" class="sb-item {{ request()->routeIs('store.materials') ? 'active' : '' }}">
            <i class="fas fa-book si"></i> Materials
        </a>

        @php
        use App\Models\DraftTimetable;
        use App\Models\Timetable;

        $latestDraft = DraftTimetable::latest()->first();
        $latestFinal = Timetable::latest()->first();
        @endphp

        @if($latestFinal && (!$latestDraft || $latestFinal->created_at >= $latestDraft->created_at))
            <a href="{{ route('store.timetable') }}" class="sb-item {{ request()->routeIs('store.timetable') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt si"></i> Timetable
            </a>
        @elseif($latestDraft)
            <a href="{{ route('draft.index') }}" class="sb-item {{ request()->routeIs('draft.index') ? 'active' : '' }}">
                <i class="fas fa-calendar-week si"></i> Draft Timetable
            </a>
        @endif
    </div> --}}


     


    <div class="sb-section" style="margin-top:10px;">
      
        <div class="sb-label">Account</div>

         @php $isEmailVerified = auth()->check() && !empty(auth()->user()->email_verified_at); @endphp

@if(!$isEmailVerified)
        <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button
                    type="submit"
                    style="width:100%; background:#16a34a; border:none; border-radius:10px; color:#fff; font-size:14px; font-weight:600; padding:13px; cursor:pointer; margin-bottom:10px;"
                >
                   Verify Your Email
                </button>
            </form>
@endif
        <a href="{{ route('profile') }}" class="sb-item {{ request()->routeIs('profile') ? 'active' : '' }}">
            <i class="fas fa-user si"></i> My Profile
        </a>
        <a href="{{ route('plan.index') }}" class="sb-item {{ request()->routeIs('plan.*') ? 'active' : '' }}">
            <i class="fas fa-layer-group si"></i> Plan
        </a>
        <form action="{{ route('logout') }}" method="POST" style="margin:0;">
            @csrf
            <button type="submit" class="sb-item" style="cursor:pointer;">
                <i class="fas fa-sign-out-alt si"></i> Sign Out
            </button>
        </form>
    </div>

    <div class="sb-bottom">
        <a href="{{ route('profile') }}" class="sb-user" style="text-decoration:none;">
            <div class="user-av">{{ strtoupper(substr(auth()->user()->first_name, 0, 1) . substr(auth()->user()->last_name, 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</div>
                <div class="user-meta">
                    {{ auth()->user()->info?->level ?? 'Student' }}
                    {{ auth()->user()->info?->department ? '· ' . auth()->user()->info->department : '' }}
                </div>
            </div>
            <span style="margin-left:auto;font-size:.85rem;color:var(--gray-400);">
                <i class="fas fa-ellipsis-h"></i>
            </span>
        </a>
    </div>
</aside>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


  <!-- ── MAIN ── -->
  <div class="main">
    
   
    <!-- TOPBAR -->
    <header class="topbar">
      <button class="hamburger" onclick="toggleSidebar()" aria-label="Menu">
        <span></span><span></span><span></span>
      </button>
      <div class="page-title">@yield('page-title')</div>
     
      <div class="topbar-actions">

        {{-- <div class="icon-btn" onclick="toast('🔔 Coming soon')">
          🔔<div class="notif-dot"></div>
        </div> --}}
        {{-- <div class="icon-btn" onclick="toast('🛒 Coming soon')">🛒</div> --}}
        <a href="{{ route('profile') }}" class="user-av" style="text-decoration:none;cursor:pointer;">
          {{ strtoupper(substr(auth()->user()->first_name, 0, 1) . substr(auth()->user()->last_name, 0, 1)) }}
        </a>
      </div>
    </header>

<!-- CONTENT -->
    <div class="content">
       @include('parties.alerts')
     @php $isEmailVerified = auth()->check() && !empty(auth()->user()->email_verified_at); @endphp

@if(!$isEmailVerified)
    @include('popup.emailverified')
@endif
      @yield('dashboard-content')
         @if ($errors->any())
        <div class="sv-error show" style="display:flex;">
          <span>⚠</span>
          <span>{{ $errors->first() }}</span>
        </div>
        @endif

        @if (session('status'))
        <div class="sv-error show" style="display:flex;background:var(--green-light);color:var(--green);border-color:rgba(26,107,60,.25);">
          <span>✅</span>
          <span>{{ session('status') }}</span>
        </div>
        @endif
    </div><!-- /main -->
<!-- Default Statcounter code for School
https://psalmedu.com -->
<script type="text/javascript">
var sc_project=13197812; 
var sc_invisible=1; 
var sc_security="6e7226fb"; 
</script>
<script type="text/javascript"
src="https://www.statcounter.com/counter/counter.js"
async></script>
<noscript><div class="statcounter"><a title="Web Analytics"
href="https://statcounter.com/" target="_blank"><img
class="statcounter"
src="https://c.statcounter.com/13197812/0/6e7226fb/1/"
alt="Web Analytics"
referrerPolicy="no-referrer-when-downgrade"></a></div></noscript>
<!-- End of Statcounter Code -->
    <!-- Cart Drawer -->
{{-- /  @include('parties.cart-drawer') --}}
@include('parties.cart-js')
    <style>
    /* Cart Drawer Styles */
    .cart-fab {
      position: fixed;
      bottom: 24px;
      right: 24px;
      width: 56px;
      height: 56px;
      background: var(--green);
      color: #fff;
      border-radius: 50%;
      display: grid;
      place-items: center;
      box-shadow: 0 8px 32px rgba(26,107,60,.4);
      cursor: pointer;
      z-index: 999;
      text-decoration: none;
      transition: all .2s;
      font-size: 1.1rem;
    }
    .cart-fab:hover { transform: scale(1.05); box-shadow: 0 12px 40px rgba(26,107,60,.5); }
    .cart-badge {
      position: absolute;
      top: 12px;
      right: 12px;
      background: #EF4444;
      color: #fff;
      border-radius: 50%;
      width: 20px;
      height: 20px;
      font-size: .7rem;
      font-weight: 700;
      display: grid;
      place-items: center;
    }
    .cart-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,.5);
      z-index: 998;
      opacity: 0;
      visibility: hidden;
      transition: all .3s;
    }
    .cart-overlay.active { opacity: 1; visibility: visible; }
    .cart-drawer {
      position: fixed;
      right: -400px;
      top: 0;
      bottom: 0;
      width: 380px;
      background: var(--white);
      border-left: 1.5px solid var(--border);
      z-index: 999;
      transition: right .3s;
      display: flex;
      flex-direction: column;
    }
    .cart-drawer.open { right: 0; }
    .cd-header {
      padding: 20px 24px;
      border-bottom: 1.5px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .cd-header h3 { font-family: 'Playfair Display', serif; font-weight: 700; color: var(--text); margin: 0; }
    .cd-close { background: none; border: none; font-size: 1.3rem; cursor: pointer; color: var(--gray-500); padding: 4px; border-radius: 50%; width: 36px; height: 36px; display: grid; place-items: center; transition: all .2s; }
    .cd-close:hover { background: var(--gray-100); color: var(--text); }
    .cd-body { flex: 1; padding: 24px; overflow-y: auto; }
    .cd-loading, .cd-empty { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 60px 20px; gap: 16px; color: var(--gray-500); }
    .cd-spinner { width: 36px; height: 36px; border: 3px solid var(--gray-200); border-top-color: var(--green); border-radius: 50%; animation: spin 1s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
    .cd-empty-icon { font-size: 3.5rem; }
    .cd-browse-btn { background: var(--green); color: #fff; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 700; }
    .cd-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 16px 0;
      border-bottom: 1.5px solid var(--border);
    }
    .cd-item:last-child { border: none; }
    .cd-item-thumb { width: 48px; height: 48px; border-radius: 10px; background: var(--green-light); display: grid; place-items: center; font-size: 1.4rem; flex-shrink: 0; }
    .cd-item-code { font-size: .75rem; font-weight: 700; color: var(--green); text-transform: uppercase; letter-spacing: .05em; }
    .cd-item-name { font-size: .88rem; font-weight: 600; color: var(--text); margin-top: 2px; }
    .cd-item-price { font-weight: 700; color: var(--text); margin-left: auto; }
    .cd-item-remove { background: none; border: 1.5px solid var(--border); color: var(--gray-500); width: 32px; height: 32px; border-radius: 50%; display: grid; place-items: center; cursor: pointer; font-size: .9rem; flex-shrink: 0; }
    .cd-item-remove:hover { background: var(--gray-100); color: #EF4444; border-color: #EF4444; }
    .cd-footer { padding: 24px; border-top: 1.5px solid var(--border); background: var(--white); }
    .cd-total-row { display: flex; justify-content: space-between; margin-bottom: 16px; font-size: .92rem; font-weight: 600; }
    .cd-total-amount { font-size: 1.2rem; font-weight: 800; font-family: 'Playfair Display', serif; color: var(--green); }
    .cd-checkout-btn {
      display: block;
      background: var(--green);
      color: #fff;
      text-align: center;
      padding: 14px;
      border-radius: 10px;
      font-weight: 700;
      text-decoration: none;
      margin-bottom: 12px;
      box-shadow: 0 4px 16px rgba(26,107,60,.3);
      transition: all .2s;
    }
    .cd-checkout-btn:hover { background: var(--green-mid); transform: translateY(-1px); }
    .cd-continue-btn {
      width: 100%;
      background: transparent;
      border: 1.5px solid var(--border);
      color: var(--text);
      padding: 12px;
      border-radius: 10px;
      font-weight: 600;
      cursor: pointer;
      transition: all .2s;
    }
    .cd-continue-btn:hover { background: var(--gray-50); }
    @media(max-width: 768px) {
      .cart-drawer { width: 100%; right: -100%; }
      .cart-drawer.open { right: 0; }
    }
    </style>
</div><!-- /app -->

<!-- Toast -->
<div class="toast" id="toast">
  <span id="toast-icon"></span>
  <span id="toast-msg"></span>
</div>

@push('scripts')
<script>
function toast(msg) {
  const t = document.getElementById('toast');
  const parts = msg.split(' ');
  document.getElementById('toast-icon').textContent = parts[0];
  document.getElementById('toast-msg').textContent = parts.slice(1).join(' ');
  t.classList.add('show');
  clearTimeout(t._t);
  t._t = setTimeout(() => t.classList.remove('show'), 3200);
}

function toggleSidebar(force) {
  const el = document.getElementById('sidebar');
  if (!el) return;
  // If force === true/false, set explicitly; otherwise toggle
  if (force === true) el.classList.add('open');
  else if (force === false) el.classList.remove('open');
  else el.classList.toggle('open');
}

// Close sidebar when tapping outside (mobile)
document.addEventListener('click', function(e) {
  const sidebar = document.getElementById('sidebar');
  const hamburger = e.target && e.target.closest ? e.target.closest('.hamburger') : null;
  if (!sidebar) return;
  // Only apply on smaller screens
  if (window.innerWidth > 960) return;
  if (!sidebar.classList.contains('open')) return;
  if (hamburger) return;
  if (e.target && sidebar.contains(e.target)) return;
  toggleSidebar(false);
});

// Close sidebar on Escape
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    toggleSidebar(false);
  }
});

// Load initial cart count
document.addEventListener('DOMContentLoaded', function() {
  if (typeof loadCartCount === 'function') {
    loadCartCount();
  }
});
</script>

<link rel="stylesheet" href="{{ asset('css/plan.css') }}">
<script src="{{ asset('resources/js/cart.js') }}"></script>
@endpush
@endsection
