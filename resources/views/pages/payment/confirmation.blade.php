@extends('layouts.dashboard')
@section('title', 'Payment Confirmation')
@section('page-title', '💳 Payment Confirmation')

@section('dashboard-content')
<style>
.payment-container {
    max-width: 600px;
    margin: 0 auto;
    text-align: center;
    padding: 40px 20px;
}
.payment-icon {
    font-size: 6rem;
    margin-bottom: 24px;
}
.payment-title {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    color: var(--green);
    margin-bottom: 16px;
}
.payment-subtitle {
    font-size: 1.1rem;
    color: var(--text);
    margin-bottom: 32px;
}
.order-details {
    background: var(--white);
    border: 1.5px solid var(--border);
    border-radius: var(--r-lg);
    padding: 24px;
    margin-bottom: 32px;
    text-align: left;
}
.detail-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    font-size: .95rem;
}
.detail-row.total {
    font-weight: 700;
    font-size: 1.2rem;
    border-top: 1px solid var(--border);
    padding-top: 12px;
    margin-top: 8px;
}
.download-section {
    background: var(--green-light);
    border: 2px solid var(--green);
    border-radius: var(--r-lg);
    padding: 24px;
    margin-bottom: 24px;
}
.download-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid rgba(26,107,60,.2);
}
.download-item:last-child { border-bottom: none; }
.download-link {
    background: var(--green);
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all .2s;
}
.download-link:hover {
    background: var(--green-mid);
    transform: translateY(-1px);
}
.btn-back {
    background: var(--gray-50);
    color: var(--text);
    border: 2px solid var(--border);
    padding: 12px 32px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all .2s;
}
.btn-back:hover {
    background: var(--text);
    color: white;
    border-color: var(--text);
}
</style>

<div class="payment-container">
    <div class="payment-icon">✅</div>
    <h1 class="payment-title">Payment Successful!</h1>
    <p class="payment-subtitle">Thank you for your purchase. Your payment has been processed successfully.</p>
    
    @if(isset($order))
    <div class="order-details">
        <h3 style="margin-bottom: 20px; color: var(--text);">Order Details</h3>
        <div class="detail-row">
            <span>Order Reference:</span>
            <span>{{ $order->order_reference ?? 'N/A' }}</span>
        </div>
        <div class="detail-row">
            <span>Customer:</span>
            <span>{{ $order->customer_name ?? 'N/A' }}</span>
        </div>
        <div class="detail-row">
            <span>Email:</span>
            <span>{{ $order->customer_email ?? 'N/A' }}</span>
        </div>
        <div class="detail-row total">
            <span>Total Amount:</span>
            <span>₦{{ number_format($order->total_amount ?? 0, 0) }}</span>
        </div>
    </div>
    @endif
    
    @if(isset($purchasedItems) && count($purchasedItems) > 0)
    <div class="download-section">
        <h3 style="margin-bottom: 20px; color: var(--green);">📚 Your Downloads</h3>
        @foreach($purchasedItems as $item)
        <div class="download-item">
            <div>
                <div style="font-weight: 600; margin-bottom: 4px;">{{ $item['course_code'] }}</div>
                <div style="font-size: .9rem; color: var(--gray-500);">{{ $item['title'] }}</div>
            </div>
            <a href="{{ $item['download_url'] }}" class="download-link">Download PDF</a>
        </div>
        @endforeach
    </div>
    @endif
    
    <div style="margin-top: 32px;">
        <a href="{{ route('store.summaries') }}" class="btn-back">← Continue Shopping</a>
        <a href="{{ route('dashboard') }}" class="btn-back" style="margin-left: 12px;">Go to Dashboard</a>
    </div>
</div>

@endsection

