{{-- resources/views/emails/verify-email.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verify Your Email</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; padding: 40px; }
        .container { background: #fff; max-width: 560px; margin: auto; padding: 40px; border-radius: 8px; }
        .btn { display: inline-block; padding: 14px 28px; background: #4f46e5; color: #fff; text-decoration: none; border-radius: 6px; font-weight: bold; }
        .footer { margin-top: 32px; font-size: 13px; color: #888; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hi {{ $userName }},</h2>
        <p>Please click the button below to verify your email address.</p>
        <p>
            <a href="{{ $verificationUrl }}" class="btn">Verify Email Address</a>
        </p>
        <p>This link expires in 60 minutes.</p>
        <div class="footer">
            <p>If you did not create an account, no action is needed.</p>
            <p>Or copy this link: {{ $verificationUrl }}</p>
        </div>
    </div>
</body>
</html>