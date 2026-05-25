<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Challenge Invite</title>
</head>
<body style="margin:0;padding:0;background:#f6f7fb;font-family:Arial,Helvetica,sans-serif;">
  <div style="max-width:640px;margin:0 auto;padding:24px;">
    <div style="background:#ffffff;border:1px solid #e5e7eb;border-radius:14px;padding:24px;">
      <div style="font-size:14px;color:#6b7280;margin-bottom:10px;">
        ⚡ PsalmEdu Study Challenge
      </div>

      <h1 style="margin:0 0 10px 0;font-size:22px;line-height:1.3;color:#111827;">
        <span style="font-weight:800;font-size:22px;">
          {{ $challengerFirstName }} scored {{ $challengerScore }}% — think you can beat that?
        </span>
      </h1>

      <p style="margin:0 0 18px 0;color:#374151;font-size:15px;line-height:1.6;">
        {{ $challengerFirstName }} wants a rematch—if you accept, you’ll play the exact same mock set and scores will be compared instantly.
      </p>

      <div style="margin:18px 0;">
        <a href="{{ route('challenge.play', ['challenge' => $challenge->id, 'role' => 'opponent']) }}"
           style="display:inline-block;background:#1A6B3C;color:#ffffff;text-decoration:none;padding:12px 16px;border-radius:10px;font-weight:700;">
          Accept &amp; Play
        </a>
      </div>

      <p style="margin:0;color:#6b7280;font-size:12.5px;line-height:1.6;">
        This challenge expires after 48 hours. Don’t wait too long!
      </p>
    </div>

    <div style="text-align:center;color:#9ca3af;font-size:12px;margin-top:16px;">
      PsalmEdu
    </div>
  </div>
</body>
</html>
