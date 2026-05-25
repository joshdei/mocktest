@extends('layouts.dashboard')
@section('title', 'Payment Success')
@section('page-title', '✅ Payment Successful')
@php use App\Models\SummaryPdf; @endphp

@section('dashboard-content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap');

.success-wrap {
    max-width: 680px;
    margin: 0 auto;
    padding: 40px 20px 60px;
    font-family: 'DM Sans', sans-serif;
}

/* Hero */
.success-hero {
    text-align: center;
    margin-bottom: 40px;
}
.success-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #f0fdf4;
    border: 1.5px solid #bbf7d0;
    color: #15803d;
    font-size: .8rem;
    font-weight: 600;
    letter-spacing: .06em;
    text-transform: uppercase;
    padding: 6px 16px;
    border-radius: 40px;
    margin-bottom: 24px;
}
.success-badge::before {
    content: '';
    width: 7px; height: 7px;
    background: #22c55e;
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 0 0 3px #bbf7d0;
}
.success-title {
    font-family: 'Playfair Display', serif;
    font-size: 2.4rem;
    color: var(--text);
    margin-bottom: 12px;
    line-height: 1.2;
}
.success-sub {
    font-size: 1rem;
    color: var(--gray-500);
    line-height: 1.7;
    max-width: 460px;
    margin: 0 auto;
}

/* Divider */
.section-label {
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--gray-500);
    margin-bottom: 14px;
}

/* Download Cards */
.download-grid {
    display: grid;
    gap: 14px;
    margin-bottom: 32px;
}
.download-card {
    background: var(--white);
    border: 1.5px solid var(--border);
    border-radius: 16px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: border-color .2s, box-shadow .2s;
}
.download-card:hover {
    border-color: var(--green);
    box-shadow: 0 4px 20px rgba(26,107,60,.1);
}
.dl-icon {
    width: 52px; height: 52px;
    border-radius: 14px;
    background: #f0fdf4;
    display: grid;
    place-items: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}
.dl-info { flex: 1; min-width: 0; }
.dl-code {
    font-size: .75rem;
    font-weight: 700;
    color: var(--green);
    text-transform: uppercase;
    letter-spacing: .07em;
}
.dl-title {
    font-size: .95rem;
    font-weight: 600;
    color: var(--text);
    margin-top: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.dl-size {
    font-size: .78rem;
    color: var(--gray-500);
    margin-top: 2px;
}
.dl-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: var(--green);
    color: #fff;
    text-decoration: none;
    padding: 10px 18px;
    border-radius: 10px;
    font-size: .85rem;
    font-weight: 600;
    white-space: nowrap;
    flex-shrink: 0;
    transition: background .2s, transform .15s;
}
.dl-btn:hover {
    background: var(--green-mid);
    transform: translateY(-1px);
}
.dl-btn span { font-size: 1rem; }

/* Order Summary Box */
.order-box {
    background: var(--white);
    border: 1.5px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
    margin-bottom: 32px;
}
.order-box-head {
    background: #f8fdf9;
    border-bottom: 1.5px solid var(--border);
    padding: 14px 20px;
    font-size: .8rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--gray-500);
}
.order-box-body { padding: 20px; }
.order-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    font-size: .9rem;
    border-bottom: 1px solid var(--border);
}
.order-row:last-child { border-bottom: none; padding-bottom: 0; }
.order-row-label { color: var(--gray-500); }
.order-row-value { font-weight: 600; color: var(--text); }
.order-row-value.total {
    font-family: 'Playfair Display', serif;
    font-size: 1.3rem;
    color: var(--green);
}
.ref-pill {
    background: #f0fdf4;
    color: #15803d;
    border: 1px solid #bbf7d0;
    border-radius: 6px;
    padding: 3px 10px;
    font-size: .82rem;
    font-weight: 600;
    font-family: monospace;
}

/* Action Buttons */
.action-row {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
}
.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--green);
    color: #fff;
    text-decoration: none;
    padding: 13px 28px;
    border-radius: 12px;
    font-family: 'DM Sans', sans-serif;
    font-weight: 600;
    font-size: .95rem;
    transition: background .2s, transform .15s;
    border: none;
    cursor: pointer;
}
.btn-primary:hover { background: var(--green-mid); transform: translateY(-2px); }
.btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--white);
    color: var(--text);
    text-decoration: none;
    padding: 13px 28px;
    border-radius: 12px;
    font-family: 'DM Sans', sans-serif;
    font-weight: 600;
    font-size: .95rem;
    border: 1.5px solid var(--border);
    transition: all .2s;
    cursor: pointer;
}
.btn-secondary:hover { border-color: var(--text); background: var(--text); color: #fff; }

.dl-status {
    font-size: .78rem;
    margin-top: 2px;
}
.dl-status.unavailable {
    color: #ef4444;
    font-weight: 500;
}
.dl-btn.disabled {
    background: #9ca3af !important;
    cursor: not-allowed;
    transform: none !important;
    pointer-events: none;
}
.dl-btn.disabled:hover {
    background: #9ca3af !important;
    transform: none !important;
}

/* Confetti animation */
.confetti-row {
    display: flex;
    justify-content: center;
    gap: 6px;
    margin-bottom: 20px;
}
.confetti-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    animation: confettiBounce 1.2s ease-in-out infinite;
}
.confetti-dot:nth-child(1) { background: #22c55e; animation-delay: 0s; }
.confetti-dot:nth-child(2) { background: #f59e0b; animation-delay: .15s; }
.confetti-dot:nth-child(3) { background: #3b82f6; animation-delay: .3s; }
.confetti-dot:nth-child(4) { background: #ec4899; animation-delay: .45s; }
.confetti-dot:nth-child(5) { background: #8b5cf6; animation-delay: .6s; }
@keyframes confettiBounce {
    0%, 100% { transform: translateY(0); opacity: .6; }
    50% { transform: translateY(-10px); opacity: 1; }
}

/* Fade-in stagger */
.fade-in { animation: fadeUp .5s ease both; }
.fade-in:nth-child(1) { animation-delay: .05s; }
.fade-in:nth-child(2) { animation-delay: .15s; }
.fade-in:nth-child(3) { animation-delay: .25s; }
.fade-in:nth-child(4) { animation-delay: .35s; }
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}

@media(max-width: 540px) {
    .success-title { font-size: 1.8rem; }
    .download-card { flex-wrap: wrap; }
    .dl-btn { width: 100%; justify-content: center; }
    .action-row { flex-direction: column; align-items: stretch; }
    .btn-primary, .btn-secondary { justify-content: center; }
}
</style>

<div class="success-wrap">

    {{-- Hero --}}
    <div class="success-hero fade-in">
        <div class="confetti-row">
            <div class="confetti-dot"></div>
            <div class="confetti-dot"></div>
            <div class="confetti-dot"></div>
            <div class="confetti-dot"></div>
            <div class="confetti-dot"></div>
        </div>
        <div class="success-badge">Payment confirmed</div>
        <h1 class="success-title">Your summaries are ready! 🎉</h1>
        <p class="success-sub">Payment processed successfully. Download your study materials below — they're yours forever.</p>
    </div>

    {{-- Downloads --}}
    @if(isset($purchasedItems) && count($purchasedItems) > 0)
    <div class="fade-in">
        <div class="section-label">📥 Your Downloads</div>
        <div class="download-grid">
            @foreach($purchasedItems as $item)
@php
                $icon = '📚';
                if(str_contains($item['course_code'], 'MTH')) $icon = '📐';
                elseif(str_contains($item['course_code'], 'LAW')) $icon = '⚖️';
                elseif(str_contains($item['course_code'], 'BIO')) $icon = '🧬';
                elseif(str_contains($item['course_code'], 'BUS')) $icon = '💼';
                elseif(str_contains($item['course_code'], 'CIT')) $icon = '💻';

                $pdf = SummaryPdf::find($item['id']);
               
                $fullPath = public_path('storage/' . $pdf->file_path);
                $fileExists = $filePath && file_exists($filePath);
                $fileSize = $fileExists ? number_format(filesize($filePath) / 1024, 1) . ' KB' : '';
            @endphp

      
            <div class="download-card">
                <div class="dl-icon">{{ $icon }}</div>
                <div class="dl-info">
                    <div class="dl-code">{{ $item['course_code'] }}</div>
                    <div class="dl-title">{{ $item['title'] }}</div>
                    @if($fileSize)
                    <div class="dl-size">{{ $fileSize }}</div>
                    @endif
                    @if(!$fileExists)
                    <div class="dl-status unavailable">File not available on server yet</div>
                    @endif
                </div>
                @if($fileExists)
                <a href="{{ $item['download_url'] }}" class="dl-btn">
                    <span>↓</span> Download PDF
                </a>
                @else
                <div class="dl-btn disabled">
                    <span>⏳</span> Coming Soon
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Order Info --}}
    @if(isset($reference))
    <div class="fade-in">
        <div class="section-label">🧾 Order Details</div>
        <div class="order-box">
            <div class="order-box-head">Transaction Summary</div>
            <div class="order-box-body">
                <div class="order-row">
                    <span class="order-row-label">Reference</span>
                    <span class="ref-pill">{{ $reference }}</span>
                </div>
                <div class="order-row">
                    <span class="order-row-label">Date</span>
                    <span class="order-row-value">{{ now()->format('M d, Y · h:i A') }}</span>
                </div>
                <div class="order-row">
                    <span class="order-row-label">Total Paid</span>
                    <span class="order-row-value total">₦{{ number_format(session('order_total', 0), 0) }}</span>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Actions --}}
    <div class="action-row fade-in">
        <a href="{{ route('store.summaries') }}" class="btn-primary">🛒 Continue Shopping</a>
        <a href="{{ route('dashboard') }}" class="btn-secondary">🏠 Go to Dashboard</a>
    </div>

</div>
@endsection