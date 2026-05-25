@extends('layouts.app')

@section('title', 'Create Account')

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
      <h1>Start your<br><em>journey</em>, today.</h1>
      <p>Join thousands of students accessing over 1,200+ PDF study materials, free past questions, and personalised study timetables.</p>
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

      <div id="register-panel">
        <div class="sv-form-head">
          <h2>Create your account</h2>
          <p>Join thousands of students on {{ config('app.name') }}</p>
        </div>

        @if ($errors->any())
        <div class="sv-error show" style="display:flex;">
          <span>⚠</span>
          <span>{{ $errors->first() }}</span>
        </div>
        @endif
 <div class="sv-divider">  Register with Google</div>
        <a href="{{ route('auth.google') }}" class="sv-google">
          <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
            <path fill="#4285F4" d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844a4.14 4.14 0 0 1-1.796 2.716v2.259h2.908C16.658 14.253 17.64 11.945 17.64 9.2z"/>
            <path fill="#34A853" d="M9 18c2.43 0 4.467-.806 5.956-2.18l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 0 0 9 18z"/>
            <path fill="#FBBC05" d="M3.964 10.71A5.41 5.41 0 0 1 3.682 9c0-.593.102-1.17.282-1.71V4.958H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.042l3.007-2.332z"/>
            <path fill="#EA4335" d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 0 0 .957 4.958L3.964 7.29C4.672 5.163 6.656 3.58 9 3.58z"/>
          </svg>
          Continue with Google
        </a>
<div class="sv-divider">or sign up with email</div>
        <!-- REGISTER FORM -->
        <form id="register-form" method="POST" action="{{ route('register') }}">
          @csrf
          <div class="sv-row">
            <div class="sv-field">
              <label>First Name</label>
              <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Adaeze" required/>
            </div>
            <div class="sv-field">
              <label>Last Name</label>
              <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Okoye" required/>
            </div>
          </div>
          <div class="sv-field">
            <label>Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="you@university.edu.ng" required/>
          </div>
          <div class="sv-field">
            <label>Phone Number</label>
            <input type="tel" name="telephone" value="{{ old('telephone') }}" placeholder="08012345678" required/>
          </div>
          {{-- <div class="sv-field">
            <label>Department / Faculty</label>
            <input type="text" name="department" value="{{ old('department') }}" placeholder="e.g. Law, Medicine, Engineering" required/>
          </div> --}}
          {{-- <div class="sv-field">
            <label>Current Level</label>
            <div class="level-sel" id="level-sel">
              <div class="level-opt {{ old('level') == '100L' ? 'sel' : '' }}" onclick="pickLevel(this)">100L</div>
              <div class="level-opt {{ old('level') == '200L' || old('level') == null ? 'sel' : '' }}" onclick="pickLevel(this)">200L</div>
              <div class="level-opt {{ old('level') == '300L' ? 'sel' : '' }}" onclick="pickLevel(this)">300L</div>
              <div class="level-opt {{ old('level') == '400L' ? 'sel' : '' }}" onclick="pickLevel(this)">400L</div>
              <div class="level-opt {{ old('level') == '500L' ? 'sel' : '' }}" onclick="pickLevel(this)">500L</div>
            </div>
            <input type="hidden" name="level" id="reg-level" value="{{ old('level', '200L') }}"/>
          </div> --}}
          <div class="sv-field">
            <label>Password</label>
            <input type="password" name="password" placeholder="Min. 8 characters" required minlength="8"/>
          </div>
          <div class="sv-field">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" placeholder="Repeat your password" required minlength="8"/>
          </div>
          <button type="submit" class="sv-btn" id="reg-btn">Create My Account</button>
          {{-- @include('parties.policy') --}}
          <p class="sv-terms">By signing up you agree to our <a href="#terms" onclick="showPol('terms')">Terms of Service</a> and <a href="#privacy" onclick="showPol('privacy')">Privacy Policy</a>.</p>
        </form>

       

        <div class="sv-footer">
          Already have an account? <a href="{{ route('login') }}">Sign in →</a>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  function pickLevel(el) {
    document.querySelectorAll('.level-opt').forEach(function(x){ x.classList.remove('sel'); });
    el.classList.add('sel');
    document.getElementById('reg-level').value = el.textContent;
  }
</script>
@endpush

