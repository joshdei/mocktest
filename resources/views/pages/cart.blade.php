@extends('layouts.dashboard')
@section('title', 'Shopping Cart')
@section('page-title', '🛒 Shopping Cart')

@section('dashboard-content')
<style>
/* TWO-COLUMN LAYOUT */
.cart-container {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 24px;
    align-items: start;
    max-width: 900px;
    margin: 0 auto;
}

.cart-empty { text-align: center; padding: 60px 20px; color: var(--gray-500); }
.cart-empty-icon { font-size: 4rem; margin-bottom: 20px; }
.cart-empty h3 { font-family: 'Playfair Display', serif; font-size: 1.5rem; margin-bottom: 12px; color: var(--text); }

.cart-items {
    background: var(--white);
    border: 1.5px solid var(--border);
    border-radius: var(--r-lg);
    padding: 28px;
}

.cart-item { display: flex; align-items: center; gap: 16px; padding: 20px 0; border-bottom: 1.5px solid var(--border); }
.cart-item:last-child { border-bottom: none; }
.cart-thumb { width: 54px; height: 54px; border-radius: 12px; background: var(--green-light); display: grid; place-items: center; font-size: 1.5rem; flex-shrink: 0; }
.cart-details { flex: 1; }
.cart-code { font-size: .78rem; font-weight: 700; color: var(--green); text-transform: uppercase; letter-spacing: .06em; }
.cart-title { font-size: .95rem; font-weight: 600; color: var(--text); margin-top: 4px; }
.cart-actions { display: flex; align-items: center; gap: 16px; margin-left: auto; }

/* Quantity Controls */
.qty-controls {
    display: flex;
    align-items: center;
    gap: 4px;
    background: var(--gray-50);
    border-radius: 40px;
    padding: 3px;
    border: 1px solid var(--border);
}
.qty-btn {
    background: white;
    border: none;
    font-size: 1.1rem;
    cursor: pointer;
    color: var(--gray-700);
    width: 30px;
    height: 30px;
    display: grid;
    place-items: center;
    border-radius: 40px;
    transition: all .2s;
    font-weight: 600;
}
.qty-btn:hover { background: var(--green); color: white; }
.qty-btn:active { transform: scale(0.95); }
.qty-num {
    font-weight: 700;
    min-width: 32px;
    text-align: center;
    font-size: .95rem;
    color: var(--text);
}

/* Price */
.item-price {
    font-weight: 800;
    font-size: 1.05rem;
    color: var(--green);
    min-width: 90px;
    text-align: right;
}

/* Remove Button */
.remove-item {
    background: white;
    border: 1.5px solid var(--border);
    color: var(--gray-500);
    cursor: pointer;
    font-size: .9rem;
    width: 34px;
    height: 34px;
    display: grid;
    place-items: center;
    border-radius: 40px;
    transition: all .2s;
}
.remove-item:hover { background: #FEE2E2; color: #EF4444; border-color: #EF4444; }
.remove-item:active { transform: scale(0.95); }

/* SUMMARY PANEL */
.cart-summary {
    background: var(--white);
    border: 1.5px solid var(--border);
    border-radius: var(--r-lg);
    padding: 24px;
    position: sticky;
    top: 20px;
}

.summary-title {
    font-size: 11px;
    font-weight: 700;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: .08em;
    margin-bottom: 16px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    font-size: .9rem;
    padding: 6px 0;
    color: var(--gray-500);
}
.summary-row span:last-child { color: var(--text); font-weight: 500; }

.summary-total {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    border-top: 1.5px solid var(--border);
    padding-top: 16px;
    margin-top: 8px;
    margin-bottom: 20px;
}
.summary-total-label { font-size: 1rem; font-weight: 600; color: var(--text); }
.summary-total-amount {
    font-size: 1.5rem;
    font-weight: 800;
    font-family: 'Playfair Display', serif;
    color: var(--green);
}

/* CHECKOUT BUTTON */
.btn-cart {
    width: 100%;
    padding: 14px 18px;
    background: var(--green);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: .95rem;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    transition: all .2s;
    text-decoration: none;
}
.btn-cart:hover {
    background: var(--green-mid);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(26,107,60,.35);
}
.btn-cart:active { transform: translateY(0); }

.btn-cart-left {
    display: flex;
    align-items: center;
    gap: 10px;
}
.btn-cart-icon {
    width: 30px;
    height: 30px;
    background: rgba(255,255,255,.2);
    border-radius: 8px;
    display: grid;
    place-items: center;
    font-size: .95rem;
    flex-shrink: 0;
}
.btn-cart-arrow {
    width: 30px;
    height: 30px;
    background: rgba(255,255,255,.2);
    border-radius: 8px;
    display: grid;
    place-items: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.secure-note {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    font-size: .75rem;
    color: var(--gray-500);
    margin-top: 10px;
}

.continue-shopping {
    text-align: center;
    padding-top: 14px;
    border-top: 1.5px solid var(--border);
    margin-top: 14px;
}
.continue-shopping a {
    color: var(--green);
    font-weight: 600;
    text-decoration: none;
    font-size: .9rem;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.continue-shopping a:hover { text-decoration: underline; }

/* Loading & Notifications */
.cart-item-loading { opacity: 0.5; pointer-events: none; transition: opacity 0.2s; }
.cart-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 14px 24px;
    border-radius: 12px;
    color: white;
    z-index: 1000;
    animation: slideIn 0.3s ease;
    font-weight: 500;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    display: flex;
    align-items: center;
    gap: 10px;
}
.cart-notification::before { content: '✓'; font-weight: bold; font-size: 1.1rem; }
.cart-notification.error::before { content: '✕'; }
@keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
@keyframes slideOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(100%); opacity: 0; } }

/* Tablet */
@media(max-width: 768px) {
    .cart-container {
        grid-template-columns: 1fr;
    }
    .cart-summary {
        position: static;
    }
}

/* Mobile */
@media(max-width: 640px) {
    .cart-item { flex-wrap: wrap; gap: 12px; padding: 16px 0; }
    .cart-thumb { width: 46px; height: 46px; font-size: 1.2rem; }
    .cart-details { flex: 1; min-width: 120px; }
    .cart-actions { width: 100%; justify-content: space-between; margin-left: 0; margin-top: 6px; }
    .item-price { text-align: left; min-width: auto; flex: 1; }
    .cart-items { padding: 18px; }
    .cart-summary { padding: 18px; }
}

/* Very Small Mobile */
@media(max-width: 480px) {
    .cart-item { flex-direction: column; align-items: flex-start; }
    .cart-details { width: 100%; }
    .cart-actions { flex-wrap: wrap; justify-content: center; }
    .qty-controls { order: 1; }
    .item-price { order: 2; text-align: center; flex: auto; }
    .remove-item { order: 3; }
}
</style>

@if(empty($items) || count($items) === 0)
<div class="cart-container" style="display: block;">
  <div class="cart-empty">
    <div class="cart-empty-icon">🛒</div>
    <h3>Your cart is empty</h3>
    <p>No items in cart. Browse our summaries and add what you need!</p>
    <a href="{{ route('store.summaries') }}" class="btn-store primary" style="padding: 12px 28px; display: inline-block; margin-top: 16px;">Browse Summaries</a>
  </div>
</div>
@else
<div class="cart-container">

  {{-- LEFT: Cart Items --}}
  <div class="cart-items">
    <h3 style="font-family: 'Playfair Display', serif; font-size: 1.2rem; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1.5px solid var(--border);">
        🛍️ Your Cart <span style="font-family: inherit; font-weight: 400; color: var(--gray-500);">(<span id="cart-count">{{ count($items) }}</span> items)</span>
    </h3>

    @foreach($items as $item)
    <div class="cart-item" data-id="{{ $item->id }}" data-price="{{ $item->price }}">
      <div class="cart-thumb">
        @php
            $icon = '📚';
            if(str_contains($item->course_code ?? '', 'MTH')) $icon = '📐';
            if(str_contains($item->course_code ?? '', 'LAW')) $icon = '⚖️';
            if(str_contains($item->course_code ?? '', 'BIO')) $icon = '🧬';
            if(str_contains($item->course_code ?? '', 'BUS')) $icon = '💼';
            if(str_contains($item->course_code ?? '', 'CIT')) $icon = '💻';
        @endphp
        {{ $icon }}
      </div>
      <div class="cart-details">
        <div class="cart-code">{{ $item->course_code }}</div>
        <div class="cart-title">{{ $item->title }}</div>
      </div>
      <div class="cart-actions">
        <div class="qty-controls">
          <button class="qty-btn" data-action="decrease" data-id="{{ $item->id }}" aria-label="Decrease quantity">−</button>
          <span class="qty-num" data-id="{{ $item->id }}">{{ $item->quantity }}</span>
          <button class="qty-btn" data-action="increase" data-id="{{ $item->id }}" aria-label="Increase quantity">+</button>
        </div>
        <div class="item-price" data-id="{{ $item->id }}">
          ₦{{ number_format($item->price * $item->quantity, 0) }}
        </div>
        <button class="remove-item" data-id="{{ $item->id }}" aria-label="Remove item">🗑️</button>
      </div>
    </div>
    @endforeach
  </div>

  {{-- RIGHT: Order Summary --}}
  <div class="cart-summary">
    <div class="summary-title">Order Summary</div>

    <div class="summary-row">
        <span>Subtotal</span>
        <span id="cart-subtotal">₦{{ number_format($total, 0) }}</span>
    </div>
    <div class="summary-row">
        <span>Delivery</span>
        <span style="color: var(--green); font-weight: 600;">Free</span>
    </div>

    <div class="summary-total">
        <span class="summary-total-label">Total</span>
        <span class="summary-total-amount" id="cart-total">₦{{ number_format($total, 0) }}</span>
    </div>

<form action="{{ route('cart.process-checkout') }}" method="POST" style="width: 100%;">
        @csrf
       
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Payment Method *</label>
            <select name="payment_method" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: .95rem;">
                <option value="">Select payment method</option>
                <option value="paystack">💳 Paystack (Card / Bank Transfer)</option>
                {{-- <option value="bank_transfer">🏦 Direct Bank Transfer</option> --}}
            </select>
        </div>
        <button type="submit" class="btn-cart">
            <div class="btn-cart-left">
                <span class="btn-cart-icon">🔒</span>
                <span>Pay ₦{{ number_format($total, 0) }} Now</span>
            </div>
            <span class="btn-cart-arrow">→</span>
        </button>
    </form>
    <div class="secure-note">🛡️ Secure &amp; encrypted payment</div>

    <div class="continue-shopping">
        <a href="{{ route('store.summaries') }}">← Continue Shopping</a>
    </div>
  </div>

</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-NG').format(amount);
}

function showNotification(message, type = 'success') {
    const existing = document.querySelector('.cart-notification');
    if (existing) {
        existing.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => existing.remove(), 300);
    }
    const n = document.createElement('div');
    n.className = `cart-notification ${type === 'error' ? 'error' : ''}`;
    n.textContent = message;
    n.style.backgroundColor = type === 'success' ? '#10B981' : '#EF4444';
    document.body.appendChild(n);
    setTimeout(() => {
        n.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => n.remove(), 300);
    }, 3000);
}

function updateTotals(subtotal, total, itemCount = null) {
    document.getElementById('cart-subtotal').textContent = `₦${formatCurrency(subtotal)}`;
    document.getElementById('cart-total').textContent = `₦${formatCurrency(total)}`;
    if (itemCount !== null) {
        document.getElementById('cart-count').textContent = itemCount;
    }
}

function updateItemUI(itemId, newQuantity, itemTotal) {
    const qtySpan = document.querySelector(`.qty-num[data-id="${itemId}"]`);
    const priceSpan = document.querySelector(`.item-price[data-id="${itemId}"]`);
    if (qtySpan) qtySpan.textContent = newQuantity;
    if (priceSpan) priceSpan.textContent = `₦${formatCurrency(itemTotal)}`;
}

function removeItemFromDOM(itemId) {
    const el = document.querySelector(`.cart-item[data-id="${itemId}"]`);
    if (el) {
        el.style.transition = 'all 0.3s ease';
        el.style.opacity = '0';
        el.style.transform = 'translateX(60px)';
        setTimeout(() => el.remove(), 300);
    }
}

function updateQuantity(itemId, newQuantity) {
    if (newQuantity < 1) { removeItem(itemId); return; }

    const itemEl = document.querySelector(`.cart-item[data-id="${itemId}"]`);
    const originalQty = parseInt(itemEl.querySelector('.qty-num').textContent);
    itemEl.classList.add('cart-item-loading');

    fetch(`/cart/update/${itemId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ quantity: newQuantity })
    })
    .then(r => { if (!r.ok) throw new Error('Network error'); return r.json(); })
    .then(data => {
        if (data.success) {
            updateItemUI(itemId, data.item.quantity, data.item.price * data.item.quantity);
            updateTotals(data.cart.subtotal, data.cart.total, data.cart.item_count);
            showNotification('Cart updated!');
        } else {
            throw new Error(data.message || 'Update failed');
        }
    })
    .catch(err => {
        console.error(err);
        updateItemUI(itemId, originalQty, originalQty * parseFloat(itemEl.dataset.price));
        showNotification('Failed to update cart.', 'error');
    })
    .finally(() => itemEl.classList.remove('cart-item-loading'));
}

function removeItem(itemId) {
    if (!confirm('Remove this item from your cart?')) return;

    const itemEl = document.querySelector(`.cart-item[data-id="${itemId}"]`);
    itemEl.classList.add('cart-item-loading');

    fetch(`/cart/remove/${itemId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            removeItemFromDOM(itemId);
            const remaining = document.querySelectorAll('.cart-item').length - 1;
            if (remaining <= 0) {
                setTimeout(() => location.reload(), 400);
            } else {
                updateTotals(data.cart.subtotal, data.cart.total, remaining);
                showNotification('Item removed');
            }
        } else {
            throw new Error(data.message || 'Remove failed');
        }
    })
    .catch(err => {
        console.error(err);
        itemEl.classList.remove('cart-item-loading');
        showNotification('Failed to remove item.', 'error');
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const cartItems = document.querySelector('.cart-items');

    cartItems.addEventListener('click', function (e) {
        const qtyBtn = e.target.closest('.qty-btn');
        if (qtyBtn) {
            e.preventDefault();
            const itemId = parseInt(qtyBtn.dataset.id);
            const action = qtyBtn.dataset.action;
            const currentQty = parseInt(document.querySelector(`.qty-num[data-id="${itemId}"]`).textContent);
            updateQuantity(itemId, action === 'increase' ? currentQty + 1 : currentQty - 1);
        }

        const removeBtn = e.target.closest('.remove-item');
        if (removeBtn) {
            e.preventDefault();
            removeItem(parseInt(removeBtn.dataset.id));
        }
    });
});
</script>
@endif
@endsection