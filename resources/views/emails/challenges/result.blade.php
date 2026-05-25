<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Challenge Result</title>
</head>
<body style="margin:0;padding:0;background:#f6f7fb;font-family:Arial,Helvetica,sans-serif;">
  <div style="max-width:640px;margin:0 auto;padding:24px;">
    <div style="background:#ffffff;border:1px solid #e5e7eb;border-radius:14px;padding:24px;">
      <h1 style="margin:0 0 14px 0;font-size:22px;line-height:1.3;color:#111827;">
        @if ($winnerId === null)
          🤝 It's a Draw!
        @else
          🏆 Winner: {{ ($winnerId === ($challenger?->id ?? null)) ? ($challenger?->first_name ?? 'Challenger') : ($opponent?->first_name ?? 'Opponent') }}
        @endif
      </h1>

      <p style="margin:0 0 18px 0;color:#374151;font-size:15px;line-height:1.6;">
        Here are the scores from the same question set:
      </p>

      <table cellpadding="0" cellspacing="0" style="width:100%;border-collapse:collapse;">
        <tr>
          <td style="padding:10px 0;color:#6b7280;font-weight:700;border-bottom:1px solid #e5e7eb;width:50%;">{{ $challenger?->first_name ?? 'Challenger' }}</td>
          <td style="padding:10px 0;text-align:right;color:#1A6B3C;font-weight:800;border-bottom:1px solid #e5e7eb;width:50%;">{{ $challengerScore }}%</td>
        </tr>
        <tr>
          <td style="padding:10px 0;color:#6b7280;font-weight:700;border-bottom:1px solid #e5e7eb;width:50%;">{{ $opponent?->first_name ?? 'Opponent' }}</td>
          <td style="padding:10px 0;text-align:right;color:#374151;font-weight:800;border-bottom:1px solid #e5e7eb;width:50%;">{{ $opponentScore }}%</td>
        </tr>
      </table>

      <p style="margin:18px 0 0 0;color:#6b7280;font-size:12.5px;line-height:1.6;">
        Tip: Check your dashboard to start a rematch.
      </p>
    </div>

    <div style="text-align:center;color:#9ca3af;font-size:12px;margin-top:16px;">
      PsalmEdu
    </div>
  </div>
</body>
</html>
