<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta name="csrf-token" content="{{ csrf_token() }}"/>
<title>@yield('title', config('app.name'))</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;1,700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{
  --green:#1A6B3C;
  --green-mid:#22844B;
  --green-light:#E8F5EE;
  --green-pale:#F0FAF4;
  --white:#FFFFFF;
  --off-white:#F8F9F6;
  --gray-100:#F3F4F6;
  --gray-200:#E5E7EB;
  --gray-400:#9CA3AF;
  --gray-500:#6B7280;
  --gray-600:#4B5563;
  --gray-700:#374151;
  --text:#1C2B1E;
  --text-muted:#5A6B5E;
  --border:#DDE8E1;
  --shadow:rgba(26,107,60,.08);
  --shadow-md:rgba(26,107,60,.14);
}
body{
  font-family:'DM Sans',sans-serif;
  background:var(--off-white);
  color:var(--text);
  line-height:1.6;
  min-height:100vh;
}
.sv-login{
  min-height:100vh;
  display:grid;
  grid-template-columns:1fr 1fr;
}

/* ── LEFT PANEL ── */
.sv-left{
  background:linear-gradient(150deg,#1A6B3C 0%,#0E4D28 100%);
  display:flex;flex-direction:column;
  padding:48px 52px;
  position:relative;overflow:hidden;
  min-height:600px;
}
.sv-left::before{
  content:'';position:absolute;inset:0;
  background-image:linear-gradient(rgba(255,255,255,.04) 1px,transparent 1px),
    linear-gradient(90deg,rgba(255,255,255,.04) 1px,transparent 1px);
  background-size:48px 48px;
}
.sv-left::after{
  content:'S';
  font-family:'Playfair Display',serif;
  font-size:340px;font-weight:800;
  color:rgba(255,255,255,.04);
  position:absolute;right:-40px;bottom:-60px;
  line-height:1;pointer-events:none;
  z-index:0;
}
.sv-brand{position:relative;z-index:1;display:flex;align-items:center;gap:12px;margin-bottom:auto;}
.sv-mark{
  width:42px;height:42px;background:rgba(255,255,255,.15);
  border:1.5px solid rgba(255,255,255,.3);
  border-radius:11px;display:grid;place-items:center;
  font-family:'Playfair Display',serif;font-size:20px;font-weight:800;color:#fff;
}
.sv-brand-name{font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:#fff;}
.sv-brand-name span{color:rgba(255,255,255,.6);}
.sv-hero{position:relative;z-index:1;margin-top:auto;padding-bottom:8px;}
.sv-hero h1{
  font-family:'Playfair Display',serif;
  font-size:2.6rem;font-weight:800;
  color:#fff;line-height:1.15;margin-bottom:16px;
}
.sv-hero h1 em{font-style:italic;color:rgba(255,255,255,.7);}
.sv-hero p{font-size:.92rem;color:rgba(255,255,255,.65);line-height:1.7;max-width:340px;margin-bottom:28px;}
.sv-pills{display:flex;flex-wrap:wrap;gap:10px;}
.sv-pill{
  background:rgba(255,255,255,.1);
  border:1px solid rgba(255,255,255,.2);
  color:#fff;font-size:.76rem;font-weight:600;
  padding:6px 14px;border-radius:20px;
  letter-spacing:.02em;
}

/* ── RIGHT PANEL ── */
.sv-right{
  display:flex;align-items:center;justify-content:center;
  padding:48px 52px;
  background:var(--white);
}
.sv-form-wrap{width:100%;max-width:400px;}
.sv-form-head{margin-bottom:32px;}
.sv-form-head h2{
  font-family:'Playfair Display',serif;
  font-size:1.8rem;font-weight:800;
  color:var(--text);margin-bottom:6px;
}
.sv-form-head p{font-size:.88rem;color:var(--text-muted);}

/* ── FORM FIELDS ── */
.sv-field{margin-bottom:18px;}
.sv-field label{
  display:block;font-size:.78rem;font-weight:600;
  color:var(--text-muted);margin-bottom:7px;
  letter-spacing:.04em;text-transform:uppercase;
}
.sv-field input,.sv-field select{
  width:100%;padding:11px 14px;
  border:1.5px solid var(--border);
  border-radius:10px;font-size:.9rem;
  font-family:'DM Sans',sans-serif;
  color:var(--text);background:var(--white);
  transition:border-color .2s,box-shadow .2s;
  outline:none;
  -webkit-appearance:none;appearance:none;
}
.sv-field input:focus,.sv-field select:focus{
  border-color:var(--green);
  box-shadow:0 0 0 3px rgba(26,107,60,.12);
}
.sv-field input::placeholder{color:var(--gray-400);}
.sv-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;}

/* ── LEVEL SELECTOR ── */
.level-sel{display:grid;grid-template-columns:repeat(5,1fr);gap:6px;}
.level-opt{
  text-align:center;padding:8px 4px;border-radius:8px;
  border:1.5px solid var(--border);
  font-size:.78rem;font-weight:600;cursor:pointer;
  color:var(--text-muted);transition:all .18s;
  background:var(--white);
  font-family:'DM Sans',sans-serif;
}
.level-opt:hover{border-color:var(--green);color:var(--green);}
.level-opt.sel{background:var(--green-light);color:var(--green);border-color:rgba(26,107,60,.3);}

/* ── FORGOT ── */
.sv-forgot{
  font-size:.78rem;color:var(--green);font-weight:600;
  text-align:right;margin-top:-10px;margin-bottom:20px;
  cursor:pointer;text-decoration:none;display:block;
}
.sv-forgot:hover{text-decoration:underline;}

/* ── BUTTONS ── */
.sv-btn{
  width:100%;padding:13px;
  background:var(--green);color:#fff;
  border:none;border-radius:10px;
  font-size:.92rem;font-weight:700;
  font-family:'DM Sans',sans-serif;
  cursor:pointer;transition:all .2s;
  box-shadow:0 4px 16px rgba(26,107,60,.3);
  letter-spacing:.02em;
}
.sv-btn:hover{background:#0E4D28;transform:translateY(-1px);box-shadow:0 6px 20px rgba(26,107,60,.38);}
.sv-btn:active{transform:translateY(0);}
.sv-btn:disabled{opacity:.6;cursor:not-allowed;transform:none;}

.sv-divider{
  display:flex;align-items:center;gap:12px;
  margin:22px 0;color:var(--text-muted);font-size:.78rem;
}
.sv-divider::before,.sv-divider::after{content:'';flex:1;height:1px;background:var(--border);}

.sv-google{
  width:100%;padding:11px;
  background:var(--white);
  border:1.5px solid var(--border);
  border-radius:10px;font-size:.88rem;font-weight:600;
  font-family:'DM Sans',sans-serif;cursor:pointer;
  color:var(--text);
  display:flex;align-items:center;justify-content:center;gap:10px;
  transition:all .2s;
}
.sv-google:hover{border-color:rgba(26,107,60,.35);background:var(--off-white);}

/* ── ERROR BOX ── */
.sv-error{
  background:#FEF2F2;border:1px solid rgba(239,68,68,.25);
  color:#B91C1C;font-size:.8rem;font-weight:500;
  padding:10px 13px;border-radius:8px;margin-bottom:16px;
  display:none;align-items:center;gap:8px;
}
.sv-error.show{display:flex;}

/* ── SUCCESS STATE ── */
.success-state{display:none;text-align:center;padding:20px 0;}
.success-state .s-icon{
  width:64px;height:64px;background:var(--green-light);
  border-radius:50%;display:grid;place-items:center;
  font-size:1.8rem;margin:0 auto 16px;
}
.success-state h3{
  font-family:'Playfair Display',serif;font-size:1.4rem;
  font-weight:800;color:var(--text);margin-bottom:8px;
}
.success-state p{font-size:.87rem;color:var(--text-muted);}

/* ── FOOTER LINKS ── */
.sv-footer{text-align:center;font-size:.8rem;color:var(--text-muted);margin-top:20px;}
.sv-footer a{color:var(--green);font-weight:600;text-decoration:none;}
.sv-footer a:hover{text-decoration:underline;}
.sv-terms{font-size:.72rem;color:var(--text-muted);text-align:center;margin-top:14px;line-height:1.6;}
.sv-terms a{color:var(--text-muted);text-decoration:underline;}

/* ── TOAST ── */
.sv-toast{
  position:fixed;bottom:24px;right:24px;z-index:9999;
  background:var(--white);border:1.5px solid var(--border);
  color:var(--text);padding:12px 18px;border-radius:12px;
  font-size:.85rem;font-weight:600;
  box-shadow:0 8px 32px rgba(0,0,0,.12);
  transform:translateY(70px);opacity:0;transition:all .3s;
  max-width:280px;display:flex;align-items:center;gap:9px;
}
.sv-toast.show{transform:translateY(0);opacity:1;}

/* ── RESPONSIVE ── */
@media(max-width:768px){
  .sv-login{grid-template-columns:1fr;}
  .sv-left{display:none;}
  .sv-right{padding:32px 24px;min-height:100vh;}
}
@media(max-width:400px){
  .sv-right{padding:24px 16px;}
  .sv-row{grid-template-columns:1fr;}
}
</style>
<link rel="stylesheet" href="{{ asset('css/setup.css') }}">
<link rel="stylesheet" href="{{ asset('css/dashboardstyle.css') }}">
<link rel="stylesheet" href="{{ asset('css/plan.css') }}">
<link rel="stylesheet" href="{{ asset('css/setup.css') }}">
<link rel="stylesheet" href="{{ asset('css/google-auth.css') }}">
@stack('styles')

<!-- Google Sign-In Script -->
@guest
  <script src="https://accounts.google.com/gsi/client" async defer></script>
@endguest
</head>
<body>

@yield('content')
{{-- @include('parties.popup') --}}

<!-- Google One Tap Container (Guest Only) -->
@guest
  <div id="g_id_onload"
    data-client_id="{{ config('services.google.client_id') }}"
    data-callback="handleOneTap"
    data-auto_select="true">
  </div>
  <div id="g_id_signin" data-type="standard"></div>
@endguest


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

<!-- Toast Notification -->
<div class="sv-toast" id="sv-toast">
  <span id="t-icon"></span>
  <span id="t-msg"></span>
</div>

<script>

  google.accounts.id.initialize({
    client_id: "{{ env('GOOGLE_CLIENT_ID') }}",
    callback: handleCredentialResponse
});

function handleCredentialResponse(response) {

    fetch("{{ route('auth.google.onetap') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            credential: response.credential
        })
    })
    .then(res => res.json())
    .then(data => {

        if (data.success) {
            window.location.href = data.redirect;
        } else {
            alert(data.message);
        }
    });
}


  var toastTimer;

  function showToast(msg) {
    var t = document.getElementById('sv-toast');
    var parts = msg.split(' ');
    document.getElementById('t-icon').textContent = parts[0];
    document.getElementById('t-msg').textContent = parts.slice(1).join(' ');
    t.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(function(){ t.classList.remove('show'); }, 3200);
  }

  // Google One Tap Callback
  function handleOneTap(response) {
    if (response && response.credential) {
      fetch('{{ route("auth.google.onetap") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify({ credential: response.credential })
      })
      .then(res => res.json())
      .then(data => {
        if (data.redirect) {
          window.location.href = data.redirect;
        } else if (data.error) {
          showToast('❌ ' + data.error);
        }
      })
      .catch(err => showToast('❌ Authentication failed'));
    }
  }
</script>
@stack('scripts')
</body>
</html>

