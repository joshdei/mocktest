{{-- resources/views/popup/emailverified.blade.php --}}

<div
    id="email-verify-popup"
    style="position:fixed; inset:0; z-index:9999; display:flex; align-items:center; justify-content:center; padding:1rem; background:rgba(0,0,0,0.75);"
>
    <div style="background:#0a0f1c; border:1px solid rgba(34,197,94,0.35); border-radius:16px; max-width:360px; width:100%; overflow:hidden; position:relative;">

        {{-- X Close Button --}}
        <button
            type="button"  {{-- ✅ ADDED --}}
            onclick="document.getElementById('email-verify-popup').style.display='none'"
            aria-label="Close"
            style="position:absolute; top:10px; right:10px; z-index:10; background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.15); border-radius:50%; width:30px; height:30px; display:flex; align-items:center; justify-content:center; cursor:pointer; color:#9ca3af; font-size:16px; line-height:1;"
        >
            &#x2715;
        </button>

        {{-- Top Section --}}
        <div style="width:100%; background:#0d1f12; padding:2rem 1.5rem 1.5rem; text-align:center; border-bottom:1px solid rgba(34,197,94,0.2);">
            {{-- Mail Icon --}}
            <div style="display:inline-flex; align-items:center; justify-content:center; width:64px; height:64px; background:rgba(34,197,94,0.15); border:1.5px solid rgba(34,197,94,0.4); border-radius:50%; margin-bottom:1rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#4ade80" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25H4.5a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5H4.5a2.25 2.25 0 00-2.25 2.25m19.5 0l-9.75 6.75L2.25 6.75"/>
                </svg>
            </div>

            {{-- Points Badge --}}
            <div style="display:inline-flex; align-items:center; gap:6px; background:rgba(34,197,94,0.15); border:0.5px solid rgba(34,197,94,0.3); border-radius:20px; padding:4px 12px; margin-bottom:1rem;">
                <span style="font-size:12px; color:#86efac; font-weight:600;">&#9733; +5 Bonus Points</span>
            </div>

            <h2 style="color:#ffffff; font-size:20px; font-weight:600; margin:0 0 8px;">Verify Your Email</h2>
            <p style="color:#6b7280; font-size:13px; margin:0; line-height:1.6;">
                Confirm your email address and earn
                <span style="color:#4ade80; font-weight:600;">5 bonus points</span>
                toward your exam journey.
            </p>
        </div>

        {{-- Bottom Section --}}
        <div style="padding:1.25rem 1.5rem 1.5rem; text-align:center;">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button
                    type="submit"
                    style="width:100%; background:#16a34a; border:none; border-radius:10px; color:#fff; font-size:14px; font-weight:600; padding:13px; cursor:pointer; margin-bottom:10px;"
                >
                    Check Your Inbox / Resend Link &rarr;
                </button>
            </form>

            <button
                type="button"  {{-- ✅ ADDED --}}
                onclick="document.getElementById('email-verify-popup').style.display='none'"
                style="background:transparent; border:none; color:#6b7280; font-size:12px; cursor:pointer; padding:6px;"
            >
                Maybe later
            </button>
        </div>

    </div>
</div>

<script>
    document.getElementById('email-verify-popup').addEventListener('click', function(e) {
        if (e.target === this) this.style.display = 'none';
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.getElementById('email-verify-popup').style.display = 'none';
        }
    });
</script>