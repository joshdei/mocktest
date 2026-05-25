<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>404 - Page Not Found | {{ config('app.name') }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{
  --green:#1A6B3C;--green-mid:#0E4D28;--green-light:#E8F5EE;--white:#FFFFFF;--off-white:#F8F9F6;--gray-400:#9CA3AF;--gray-500:#6B7280;--text:#1C2B1E;--text-muted:#5A6B5E;--border:#DDE8E1;--r:12px;--r-lg:18px;
}
body{font-family:'DM Sans',sans-serif;background:var(--off-white);color:var(--text);line-height:1.6;min-height:100vh;display:grid;place-items:center;padding:20px;}
.error-container{max-width:500px;width:100%;text-align:center;}
.error-hero{font-family:'Playfair Display',serif;font-size:5rem;color:var(--green-mid);margin-bottom:16px;line-height:1;}
.error-title{font-family:'Playfair Display',serif;font-size:2.2rem;font-weight:800;color:var(--text);margin-bottom:12px;}
.error-desc{font-size:1rem;color:var(--text-muted);margin-bottom:32px;line-height:1.7;max-width:380px;margin-left:auto;margin-right:auto;}
.error-card{background:linear-gradient(135deg,var(--green-light) 0%,#F0FAF4 100%);border:1.5px solid var(--border);border-radius:var(--r-lg);padding:40px 32px;margin-bottom:32px;position:relative;overflow:hidden;}
.error-card::before{content:'';position:absolute;inset:0;background-image:linear-gradient(rgba(26,107,60,.02) 1px,transparent 1px),linear-gradient(90deg,rgba(26,107,60,.02) 1px,transparent 1px);background-size:44px 44px;}
.error-actions{display:flex;flex-wrap:wrap;gap:12px;justify-content:center;}
.btn-error{width:100%;max-width:160px;padding:12px 20px;background:var(--green);color:#fff;border:none;border-radius:var(--r);font-size:.92rem;font-weight:700;font-family:'DM Sans',sans-serif;cursor:pointer;transition:all .2s;box-shadow:0 4px 16px rgba(26,107,60,.3);text-decoration:none;display:inline-flex;align-items:center;gap:8px;justify-content:center;}
.btn-error:hover{background:var(--green-mid);transform:translateY(-2px);box-shadow:0 8px 24px rgba(26,107,60,.4);}
.btn-secondary{background:var(--white);color:var(--green);border:1.5px solid var(--border);}
.btn-secondary:hover{border-color:var(--green);background:var(--green-light);}
@media(max-width:640px){.error-hero{font-size:4rem;}.error-title{font-size:1.9rem;}.error-card{padding:32px 24px;}}
</style>
</head>
<body>
<div class="error-container">
  <div class="error-card">
    <div style="position:relative;z-index:1;">
      <div class="error-hero">🔍</div>
      <h1 class="error-title">404 - Page Not Found</h1>
      <p class="error-desc">The page you are looking for doesn&apos;t exist or has been moved. Try searching or return home.</p>
      <div class="error-actions">
        <a href="/" class="btn-error"><i class="bi bi-house"></i> Go Home</a>
        <a href="/dashboard" class="btn-error"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <button class="btn-error btn-secondary" onclick="history.back()"><i class="bi bi-arrow-left"></i> Go Back</button>
      </div>
    </div>
  </div>
</div>
</body>
</html>
