<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your weekly rank update</title>
</head>
<body style="font-family: Arial, sans-serif; color:#111827;">
    <div style="max-width: 600px; margin: 0 auto; padding: 24px;">
        <h2 style="margin: 0 0 12px;">Weekly Rank Update</h2>

        <p style="margin: 0 0 12px;">
            Hi {{ $user->first_name ?? $user->name ?? 'there' }},
        </p>

        <p style="margin: 0 0 12px;">
            Your current weekly performance rank is <strong>#{{ $rank }}</strong>.
        </p>

        <p style="margin: 0 0 18px;">
            Points this week: <strong>{{ number_format($points) }}</strong>.
        </p>

        <p style="margin: 0 0 12px;">
            Keep going to boost your rank and unlock more rewards.
        </p>

        <div style="margin-top: 18px;">
            <a href="{{ url('/dashboard') }}" style="display:inline-block; background:#0f172a; color:#fff; text-decoration:none; padding:12px 16px; border-radius:8px;">
                View Dashboard
            </a>
        </div>
    </div>
</body>
</html>

