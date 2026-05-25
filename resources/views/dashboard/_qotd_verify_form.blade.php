@php
  $email = auth()->user()?->email;
@endphp

<form method="POST" action="{{ route('verification.send') }}" id="qotdEmailVerifyForm">
  @csrf

  <div class="gm-card qotd-card" style="padding:18px;">
    <div class="qotd-top-bar" style="margin-bottom:10px;">
      <span class="pill pill-green">⚡ Question of the Day</span>
    </div>

    <div class="qotd-question" style="color:var(--ink-muted); font-family:'DM Sans',sans-serif; font-size:.92rem;">
      Verify your email address to unlock the Question of the Day.
      @if($email)
        <div style="margin-top:8px;"><strong>Current email:</strong> {{ $email }}</div>
      @endif
    </div>

    <div style="display:flex; gap:10px; align-items:center; margin-top:14px; flex-wrap:wrap;">
      <button type="submit" class="qotd-submit-btn" style="cursor:pointer;">
        📩 Resend Verification Email
      </button>

      <button type="button" class="qotd-submit-btn" style="background:transparent; border:1.5px solid var(--border-md); color:var(--g700);"
        onclick="location.reload();">
        🔄 I Verified
      </button>
    </div>
  </div>
</form>

