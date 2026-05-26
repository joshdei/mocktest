<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Verify Email</title>
  <style>
    body{font-family:Arial,Helvetica,sans-serif;background:#f7faf8;margin:0;padding:0;color:#111B14}
    .wrap{max-width:720px;margin:60px auto;padding:0 16px}
    .card{background:#fff;border:1px solid rgba(26,107,60,.14);border-radius:16px;padding:24px;box-shadow:0 6px 24px rgba(10,36,22,.06)}
    h1{font-size:1.2rem;margin:0 0 10px}
    p{margin:0 0 14px;color:#3C4F42;line-height:1.6}
    .btn{display:inline-block;background:#14472C;color:#fff;text-decoration:none;padding:10px 16px;border-radius:12px;font-weight:700}
    form{margin-top:18px}
    .link{color:#1A5C38;font-weight:700}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <h1>Verify your email address</h1>
      <p>Thanks for signing up! Before you can use your account, please verify your email address.</p>

      @if (session('status'))
        <p style="background:#EEF8F3;border:1px solid rgba(26,107,60,.14);padding:12px 14px;border-radius:12px;margin-bottom:14px;">
          {{ session('status') }}
        </p>
      @endif

      <p>If you didn’t receive the email, you can request a new one.</p>

      <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button class="btn" type="submit">Resend verification email</button>
      </form>

      <p style="margin-top:16px;font-size:.95rem">
        <a class="link" href="{{ route('login') }}">Go to login</a>
      </p>
    </div>
  </div>
</body>
</html>

