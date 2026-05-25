@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="sv-login">

  <!-- ── LEFT PANEL ── -->
  <div class="sv-left">
    <div class="sv-brand">
      <div class="sv-mark">S</div>
      <div>
         <div class="sv-brand-name"><span>{{ config('app.name') }}</span></div>
      </div>
    </div>
    <div class="sv-hero">
      <h1>Reset your<br><em>password</em>.</h1>
      <p>Don't worry, it happens. Enter your email and we'll send you a link to get back into your account.</p>
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

      <div id="forgot-panel">
        <div class="sv-form-head">
          <h2>Forgot password?</h2>
          <p>Enter your email to receive a reset link</p>
        </div>

        @if (session('status'))
        <div class="sv-error show" style="display:flex;background:var(--green-light);color:var(--green);border-color:rgba(26,107,60,.25);">
          <span>✅</span>
          <span>{{ session('status') }}</span>
        </div>
        @endif

        @if ($errors->any())
        <div class="sv-error show" style="display:flex;">
          <span>⚠</span>
          <span>{{ $errors->first() }}</span>
        </div>
        @endif

        <!-- FORGOT PASSWORD FORM -->
        <form id="forgot-form" method="POST" action="{{ route('password.request') }}">
          @csrf
          <div class="sv-field">
            <label>Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="you@university.edu.ng" required/>
          </div>
          <button type="submit" class="sv-btn" id="forgot-btn">Send Reset Link</button>
        </form>

        <div class="sv-footer" style="margin-top:24px;">
          Remember your password? <a href="{{ route('login') }}">Sign in →</a>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection

