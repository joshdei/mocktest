@extends('layouts.app')

@section('title', ' Sign In')

@section('content')
<div class="sv-login">

  <!-- ── LEFT PANEL ── -->
  <div class="sv-left">
    <div class="sv-brand">
           @php
    use Illuminate\Support\Str;
    @endphp
      <div class="sv-mark"> {{ Str::upper(Str::substr(config('app.name'), 0, 1)) }}</div>
      <div>
        <div class="sv-brand-name"><span>{{ config('app.name') }}</span></div>
      </div>
    </div>
    <div class="sv-hero">
      <h1>Your academic<br><em>edge</em>, organised.</h1>
      <p>Access over 1,200+ PDF study materials, free past questions, and your personalised study timetable — all in one place.</p>
      <div class="sv-pills">
        <span class="sv-pill">📄 1,200+ PDFs</span>
        <span class="sv-pill">📝 800+ Past Questions</span>
        <span class="sv-pill">🗓 Smart Timetable</span>
        <span class="sv-pill">🎓 All Nigerian Universities</span>
      </div>
    </div>
  </div>

  <!-- ── RIGHT PANEL ── -->
  <div class="sv-right">
    <div class="sv-form-wrap">

      <div id="login-panel">
        <div class="sv-form-head">
          <h2>Welcome back</h2>
          <p>Sign in to access your dashboard</p>
        </div>

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
   <div class="sv-divider">
If you're signed into Chrome, Google will sign you in automatically</div>
        
        <a href="{{ route('auth.google') }}" class="sv-google">
          <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
            <path fill="#4285F4" d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844a4.14 4.14 0 0 1-1.796 2.716v2.259h2.908C16.658 14.253 17.64 11.945 17.64 9.2z"/>
            <path fill="#34A853" d="M9 18c2.43 0 4.467-.806 5.956-2.18l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 0 0 9 18z"/>
            <path fill="#FBBC05" d="M3.964 10.71A5.41 5.41 0 0 1 3.682 9c0-.593.102-1.17.282-1.71V4.958H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.042l3.007-2.332z"/>
            <path fill="#EA4335" d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 0 0 .957 4.958L3.964 7.29C4.672 5.163 6.656 3.58 9 3.58z"/>
          </svg>
          Continue with Google
        </a>
 <div class="sv-divider">or sign in with email</div>
        <!-- SIGN IN FORM -->
        <form id="login-form" method="POST" action="{{ route('login') }}">
          @csrf
          <div class="sv-field">
            <label>Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="you@university.edu.ng" required/>
          </div>
          <div class="sv-field">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password" required/>
          </div>
          <a class="sv-forgot" href="{{ route('password.request') }}">Forgot password?</a>
          <button type="submit" class="sv-btn" id="login-btn">Sign In {{config('app.name')}}</button>
        </form>

     

        <div class="sv-footer">
          New here? <a href="{{ route('register') }}">Create a free account →</a>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection

