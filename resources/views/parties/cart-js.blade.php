

<script>
    // ── CART STATE ──
let cartDrawerOpen = false;

function toggleCartDrawer() {
    cartDrawerOpen ? closeCartDrawer() : openCartDrawer();
}

function openCartDrawer() {
    cartDrawerOpen = true;
    document.getElementById('cartDrawer').classList.add('open');
    document.getElementById('cartOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';
    loadCartItems();
}

function closeCartDrawer() {
    cartDrawerOpen = false;
    document.getElementById('cartDrawer').classList.remove('open');
    document.getElementById('cartOverlay').classList.remove('active');
    document.body.style.overflow = '';
}

// Close on Escape key
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeCartDrawer(); });

// ── LOAD CART ITEMS INTO DRAWER ──
function loadCartItems() {
    document.getElementById('cdLoading').style.display = 'flex';
    document.getElementById('cdEmpty').style.display = 'none';
    document.getElementById('cdItems').style.display = 'none';
    document.getElementById('cdFooter').style.display = 'none';

    fetch('/cart', {
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('cdLoading').style.display = 'none';

        const items = data.items || [];
        updateCartCount(data.total_items || 0);

        if (items.length === 0) {
            document.getElementById('cdEmpty').style.display = 'flex';
            return;
        }

        // Render items
        const container = document.getElementById('cdItems');
        container.innerHTML = items.map(item => {
            const icon = getIcon(item.course_code || '');
            return `
            <div class="cd-item" id="cd-item-${item.id}">
                <div class="cd-item-thumb">${icon}</div>
                <div class="cd-item-info">
                    <span class="cd-item-code">${item.course_code || ''}</span>
                    <div class="cd-item-name">${item.title || 'Summary PDF'}</div>
                </div>
                <span class="cd-item-price">₦${Number(item.price).toLocaleString('en-NG')}</span>
                <button class="cd-item-remove" onclick="removeFromCart(${item.id})" title="Remove">✕</button>
            </div>`;
        }).join('');

        container.style.display = 'block';

        // Show total
        const total = items.reduce((sum, i) => sum + parseFloat(i.price || 0), 0);
        document.getElementById('cdTotalAmount').textContent = '₦' + total.toLocaleString('en-NG');
        document.getElementById('cdFooter').style.display = 'flex';
    })
    .catch(() => {
        document.getElementById('cdLoading').style.display = 'none';
        document.getElementById('cdEmpty').style.display = 'flex';
    });
}

function getIcon(code) {
    if (code.includes('MTH')) return '📐';
    if (code.includes('LAW')) return '⚖️';
    if (code.includes('BIO')) return '🧬';
    if (code.includes('BUS')) return '💼';
    if (code.includes('CIT')) return '💻';
    if (code.includes('MAC')) return '📡';
    if (code.includes('ECO')) return '📊';
    if (code.includes('ACC')) return '💰';
    if (code.includes('PSY')) return '🧠';
    return '📚';
}

// ── REMOVE ITEM ──
function removeFromCart(itemId) {
    const el = document.getElementById('cd-item-' + itemId);
    if (el) { el.style.opacity = '0.4'; el.style.pointerEvents = 'none'; }

    fetch(`/cart/remove/${itemId}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success !== false) {
            loadCartItems();
            showToast('Item removed from cart');
        }
    })
    .catch(() => {
        if (el) { el.style.opacity = '1'; el.style.pointerEvents = 'auto'; }
    });
}

// ── ADD TO CART ──
function addToCart(pdfId, courseCode, price) {
    const buttons = document.querySelectorAll('.add-to-cart');
    buttons.forEach(btn => {
        if (btn.getAttribute('data-id') == pdfId) {
            btn.textContent = '⏳ Adding...';
            btn.disabled = true;
        }
    });

    fetch('/cart/add', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
        body: JSON.stringify({ pdf_id: pdfId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            updateCartCount(data.cart_count);
            showToast(`✅ ${courseCode} added to cart!`);
            // Animate FAB
            const fab = document.getElementById('cartIcon');
            if (fab) {
                fab.style.transform = 'scale(1.2)';
                setTimeout(() => { fab.style.transform = ''; }, 200);
            }
        } else {
            showToast('❌ Could not add to cart', true);
        }
    })
    .catch(() => showToast('❌ Error adding to cart', true))
    .finally(() => {
        buttons.forEach(btn => {
            if (btn.getAttribute('data-id') == pdfId) {
                btn.textContent = '🛒 Add to Cart';
                btn.disabled = false;
            }
        });
    });
}

// ── CART COUNT ──
function updateCartCount(count) {
    const badge = document.getElementById('cartCount');
    if (!badge) return;
    badge.textContent = count;
    badge.style.display = count > 0 ? 'flex' : 'none';
}

function loadCartCount() {
    fetch('/cart', {
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => updateCartCount(data.total_items || 0))
    .catch(() => {});
}

// ── TOAST ──
function showToast(message, isError = false) {
    document.querySelectorAll('.toast-notification').forEach(t => t.remove());
    const el = document.createElement('div');
    el.className = 'toast-notification';
    el.textContent = message;
    el.style.background = isError ? '#ef4444' : '#1A6B3C';
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 3000);
}

// ── INIT ──
document.addEventListener('DOMContentLoaded', function() {
    loadCartCount();

    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            addToCart(
                this.getAttribute('data-id'),
                this.getAttribute('data-code'),
                this.getAttribute('data-price')
            );
        });
    });
});
</script>