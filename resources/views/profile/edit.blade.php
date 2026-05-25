@extends('layouts.dashboard')

@section('title')
@section('page-title')

@section('dashboard-content')

<style>
  /* ── Root Variables & Reset ── */
  :root {
    --primary: #1A6B3C;
    --primary-dark: #0E4D28;
    --primary-soft: rgba(26, 107, 60, 0.08);
    --gray-50: #F9FAFB;
    --gray-100: #F3F4F6;
    --gray-200: #E5E7EB;
    --gray-300: #D1D5DB;
    --gray-600: #4B5563;
    --gray-700: #374151;
    --gray-900: #111827;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --radius-lg: 1rem;
    --radius-md: 0.75rem;
    --radius-sm: 0.5rem;
  }

  /* ── Page Layout ── */
  .profile-page {
    max-width: 1000px;
    margin: 0 auto;
    padding: 1.5rem;
  }

  /* ── Hero Section ── */
  .profile-hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    border-radius: var(--radius-lg);
    padding: 2rem 2rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-lg);
  }

  .profile-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle at 20% 30%, rgba(255,255,255,0.08) 2px, transparent 2px);
    background-size: 32px 32px;
    pointer-events: none;
  }

  .hero-inner {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    position: relative;
    z-index: 1;
  }

  .avatar {
    width: 88px;
    height: 88px;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(4px);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 700;
    color: white;
    border: 2px solid rgba(255,255,255,0.3);
    box-shadow: var(--shadow-md);
  }

  .hero-text h1 {
    font-size: 1.75rem;
    font-weight: 700;
    color: white;
    margin: 0 0 0.25rem 0;
    letter-spacing: -0.01em;
  }

  .hero-text p {
    font-size: 0.9rem;
    color: rgba(255,255,255,0.8);
    margin: 0;
  }

  /* ── Alert Messages ── */
  .alert {
    padding: 1rem 1.25rem;
    border-radius: var(--radius-md);
    margin-bottom: 1.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }

  .alert-success {
    background: #ECFDF5;
    border-left: 4px solid #10B981;
    color: #065F46;
  }

  .alert-error {
    background: #FEF2F2;
    border-left: 4px solid #EF4444;
    color: #991B1B;
  }

  /* ── Cards ── */
  .card {
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
    padding: 1.75rem;
    margin-bottom: 1.5rem;
    transition: box-shadow 0.2s ease, transform 0.1s ease;
  }

  .card:hover {
    box-shadow: var(--shadow-md);
  }

  .card-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid var(--gray-100);
  }

  .card-header h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-900);
    margin: 0;
  }

  .card-header span {
    font-size: 1.25rem;
  }

  /* ── Form Grid ── */
  .form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem;
  }

  .field-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }

  .field-group label {
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--gray-600);
  }

  .field-group input,
  .field-group select {
    padding: 0.7rem 0.875rem;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--radius-sm);
    font-size: 0.875rem;
    font-family: inherit;
    color: var(--gray-900);
    background: white;
    transition: all 0.2s ease;
  }

  .field-group input:focus,
  .field-group select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(26, 107, 60, 0.15);
  }

  .field-group input::placeholder {
    color: var(--gray-300);
  }

  /* password hint */
  .password-hint {
    font-size: 0.7rem;
    color: var(--gray-600);
    margin-top: -0.25rem;
  }

  /* ── Buttons ── */
  .actions {
    display: flex;
    gap: 1rem;
    margin-top: 0.5rem;
  }

  .btn {
    padding: 0.7rem 1.5rem;
    border-radius: var(--radius-md);
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
    font-family: inherit;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
  }

  .btn-primary {
    background: var(--primary);
    color: white;
    box-shadow: var(--shadow-sm);
  }

  .btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
  }

  .btn-secondary {
    background: white;
    color: var(--gray-700);
    border: 1.5px solid var(--gray-200);
  }

  .btn-secondary:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
  }

  /* ── Mobile Responsive ── */
  @media (max-width: 768px) {
    .profile-page {
      padding: 1rem;
    }

    .hero-inner {
      flex-direction: column;
      text-align: center;
    }

    .avatar {
      width: 72px;
      height: 72px;
      font-size: 1.5rem;
    }

    .hero-text h1 {
      font-size: 1.5rem;
    }

    .form-grid {
      grid-template-columns: 1fr;
      gap: 1rem;
    }

    .card {
      padding: 1.25rem;
    }

    .actions {
      flex-direction: column;
    }

    .btn {
      width: 100%;
      justify-content: center;
    }
  }

  /* small tweak for full-width fields */
  .full-width {
    grid-column: span 2;
  }

  @media (max-width: 768px) {
    .full-width {
      grid-column: span 1;
    }
  }
</style>

<div class="profile-page">

  <!-- Profile Hero Header -->
  <div class="profile-hero">
    <div class="hero-inner">
      <div class="avatar">
        {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
      </div>
      <div class="hero-text">
        <h1>{{ $user->first_name }} {{ $user->last_name }}</h1>
        <p>{{ $info?->level ?? 'Student' }}{{ $info?->department ? ' · ' . $info->department : '' }}</p>
      </div>
    </div>
  </div>


  <form method="POST" action="{{ route('profile.update') }}">
    @csrf
    @method('PATCH')

    <!-- Personal Information Card -->
    <div class="card">
      <div class="card-header">
        <span>👤</span>
        <h3>Personal Information</h3>
      </div>
      <div class="form-grid">
        <div class="field-group">
          <label>First Name</label>
          <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
        </div>
        <div class="field-group">
          <label>Last Name</label>
          <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
        </div>
        <div class="field-group">
          <label>Email Address</label>
          <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="field-group">
          <label>Phone Number</label>
          <input type="tel" name="telephone" value="{{ old('telephone', $user->telephone) }}" required>
        </div>
      </div>
    </div>

    <!-- Academic Information Card -->
    <div class="card">
      <div class="card-header">
        <span>🎓</span>
        <h3>Academic Information</h3>
      </div>
      <div class="form-grid">
        <div class="field-group">
          <label>Matric Number</label>
          <input type="text" name="mat_number" value="{{ old('mat_number', $info?->mat_number ?? '') }}" placeholder="e.g. NOUN123456789">
        </div>
        <div class="field-group">
          <label>Username</label>
          <input type="text" name="username" value="{{ old('username', $info?->username ?? '') }}" placeholder="e.g. johndoe">
        </div>
        <div class="field-group">
          <label>Faculty</label>
          <input type="text" name="faculty" value="{{ old('faculty', $info?->faculty ?? '') }}" placeholder="e.g. Social Sciences">
        </div>
        <div class="field-group">
          <label>Department</label>
          <input type="text" name="department" value="{{ old('department', $info?->department ?? '') }}" placeholder="e.g. Law">
        </div>
        <div class="field-group">
          <label>Programme</label>
          <input type="text" name="programme" value="{{ old('programme', $info?->programme ?? '') }}" placeholder="e.g. LL.B">
        </div>
        <div class="field-group">
          <label>Current Level</label>
          <select name="level">
            <option value="">Select Level</option>
            @foreach(['100L','200L','300L','400L','500L'] as $lvl)
              <option value="{{ $lvl }}" {{ old('level', $info?->level) == $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
            @endforeach
          </select>
        </div>
        <div class="field-group">
          <label>Semester</label>
          <select name="semester">
            <option value="">Select Semester</option>
            <option value="First" {{ old('semester', $info?->semester) == 'First' ? 'selected' : '' }}>First Semester</option>
            <option value="Second" {{ old('semester', $info?->semester) == 'Second' ? 'selected' : '' }}>Second Semester</option>
          </select>
        </div>
        <div class="field-group">
          <label>Study Centre</label>
          <input type="text" name="study_centre" value="{{ old('study_centre', $info?->study_centre ?? '') }}" placeholder="e.g. Lagos Study Centre">
        </div>
        <div class="field-group full-width">
          <label>Zone</label>
          <input type="text" name="zone" value="{{ old('zone', $info?->zone ?? '') }}" placeholder="e.g. South-West">
        </div>
      </div>
    </div>

    <!-- Security Card -->
    <div class="card">
      <div class="card-header">
        <span>🔒</span>
        <h3>Change Password</h3>
      </div>
      <p class="password-hint" style="margin-bottom: 1rem;">Leave blank if you don't want to change your password.</p>
      <div class="form-grid">
        <div class="field-group">
          <label>New Password</label>
          <input type="password" name="password" placeholder="Min. 8 characters" minlength="8">
        </div>
        <div class="field-group">
          <label>Confirm New Password</label>
          <input type="password" name="password_confirmation" placeholder="Repeat new password" minlength="8">
        </div>
      </div>
    </div>

    <!-- Social Accounts Card -->
    <div class="card">
      <div class="card-header">
        <span>🔗</span>
        <h3>Connected Accounts</h3>
      </div>
      <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; background: var(--gray-50); border-radius: var(--radius-md); gap: 1rem;">
        <div style="display: flex; align-items: center; gap: 1rem;">
          <div style="width: 40px; height: 40px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid var(--gray-200);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
              <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
              <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
              <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
              <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
          </div>
          <div>
            <div style="font-weight: 600; color: var(--gray-900);">Google</div>
            @php
              $googleAccount = $user->socialAccounts()->where('provider', 'google')->first();
            @endphp
            <div style="font-size: 0.85rem; color: var(--gray-600); margin-top: 0.25rem;">
              @if($googleAccount)
                <span style="display: inline-block; background: #ECFDF5; color: #065F46; padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 500;">
                  ✓ Connected
                </span>
              @else
                <span style="display: inline-block; background: #FEF2F2; color: #991B1B; padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 500;">
                  ✗ Not Connected
                </span>
              @endif
            </div>
          </div>
        </div>
        <div>
          @if($googleAccount)
            <form method="POST" action="{{ route('auth.google.disconnect') }}" style="display: inline;">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-secondary" onclick="return confirm('Are you sure? Make sure you have set a password.')">
                Disconnect
              </button>
            </form>
          @else
            <a href="{{ route('auth.google.connect') }}" class="btn btn-primary">
              Connect Google
            </a>
          @endif
        </div>
      </div>
    </div>

    <!-- Form Actions -->
    <div class="actions">
      <a href="{{ route('dashboard') }}" class="btn btn-secondary">
        ← Cancel
      </a>
      <button type="submit" class="btn btn-primary">
        ✓ Save Changes
      </button>
    </div>

  </form>

</div>
@endsection