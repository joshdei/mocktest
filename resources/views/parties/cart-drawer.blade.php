{{-- ── FLOATING CART BUTTON ── --}}
<a href="javascript:void(0)" class="cart-fabb" id="cartIconn" onclick="toggleCartDrawerr()" aria-label="Open cart">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="9" cy="21" r="1"></circle>
        <circle cx="20" cy="21" r="1"></circle>
        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
    </svg>
    <span class="cart-badgee" id="cartCountt" style="display:none;">0</span>
</a>

{{-- ── OVERLAY ── --}}
<div class="cart-overlayy" id="cartOverlayy" onclick="toggleCartDrawerr()"></div>

{{-- ── CART DRAWER ── --}}
<div class="cart-drawerr" id="cartDrawerr">

    <div class="cd-headerr">
        <div class="cd-header-leftt">
            <div class="cd-header-iconn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1a6b3c" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
            </div>
            <span class="cd-titlee">My Cart</span>
            <span class="cd-count-pilll" id="cdCountPilll" style="display:none;">0 items</span>
        </div>
        <button class="cd-closee" onclick="toggleCartDrawerr()" aria-label="Close cart">✕</button>
    </div>

    <div class="cd-bodyy" id="cdBodyy">
        <div class="cd-loadingg" id="cdLoadingg">
            <div class="cd-spinnerr"></div>
            <p>Loading your cart...</p>
        </div>

        <div class="cd-emptyy" id="cdEmptyy" style="display:none;">
            <div class="cd-empty-iconn">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
            </div>
            <h4>Your cart is empty</h4>
            <p>Browse our materials and add what you need!</p>
            <a href="{{ route('store.summaries') }}" class="cd-browse-btnn" onclick="closeCartDrawerr()">
                Browse PDFs →
            </a>
        </div>

        <div id="cdItemss" style="display:none;"></div>
    </div>

    <div class="cd-footerr" id="cdFooterr" style="display:none;">
        <div class="cd-total-roww">
            <span class="cd-total-labell">Total</span>
            <span class="cd-total-amountt" id="cdTotalAmountt">₦0</span>
        </div>
        <a href="/cart/view" class="cd-checkout-btnn">
            <div class="cd-checkout-btn-leftt">
                <span class="cd-checkout-iconn">🔒</span>
                <span>Proceed to Checkout</span>
            </div>
            <span>→</span>
        </a>
        <button class="cd-continue-btnn" onclick="closeCartDrawerr()">← Continue Shopping</button>
    </div>
</div>

<style>
/* ── FAB ── */
.cart-fabb {
    position: fixed;
    bottom: 28px;
    right: 28px;
    width: 54px;
    height: 54px;
    background: var(--green);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    z-index: 900;
    box-shadow: 0 4px 16px rgba(26,107,60,.4);
    transition: transform .2s, box-shadow .2s;
}
.cart-fabb:hover {
    transform: scale(1.08);
    box-shadow: 0 6px 24px rgba(26,107,60,.5);
}
.cart-fabb:active { transform: scale(0.96); }

.cart-badgee {
    position: absolute;
    top: -4px;
    right: -4px;
    background: #ef4444;
    color: white;
    font-size: 10px;
    font-weight: 700;
    min-width: 18px;
    height: 18px;
    border-radius: 20px;
    padding: 0 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid white;
    line-height: 1;
}

/* ── OVERLAY ── */
.cart-overlayy {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0);
    z-index: 910;
    pointer-events: none;
    transition: background .3s;
}
.cart-overlayy.active {
    background: rgba(0,0,0,0.3);
    pointer-events: all;
}

/* ── DRAWER ── */
.cart-drawerr {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    width: 360px;
    max-width: 100vw;
    background: var(--white);
    z-index: 920;
    display: flex;
    flex-direction: column;
    transform: translateX(100%);
    transition: transform .3s cubic-bezier(.4,0,.2,1);
    border-left: 1.5px solid var(--border);
}
.cart-drawerr.open {
    transform: translateX(0);
    box-shadow: -8px 0 40px rgba(0,0,0,.12);
}

/* Header */
.cd-headerr {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 20px 16px;
    border-bottom: 1.5px solid var(--border);
    flex-shrink: 0;
}
.cd-header-leftt {
    display: flex;
    align-items: center;
    gap: 10px;
}
.cd-header-iconn {
    width: 34px;
    height: 34px;
    background: #f0fdf4;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.cd-titlee {
    font-size: 15px;
    font-weight: 700;
    color: var(--text);
}
.cd-count-pilll {
    background: var(--green);
    color: white;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 9px;
    border-radius: 20px;
    letter-spacing: .02em;
}
.cd-closee {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: var(--gray-50);
    border: 1px solid var(--border);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-500);
    font-size: 13px;
    transition: all .15s;
    flex-shrink: 0;
}
.cd-closee:hover {
    background: #fee2e2;
    color: #ef4444;
    border-color: #fca5a5;
}

/* Body */
.cd-bodyy {
    flex: 1;
    overflow-y: auto;
    padding: 16px 20px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.cd-bodyy::-webkit-scrollbar { width: 4px; }
.cd-bodyy::-webkit-scrollbar-track { background: transparent; }
.cd-bodyy::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

/* Loading */
.cd-loadingg {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 48px 0;
    color: var(--gray-500);
    font-size: .9rem;
}
.cd-spinnerr {
    width: 28px;
    height: 28px;
    border: 3px solid var(--border);
    border-top-color: var(--green);
    border-radius: 50%;
    animation: cdSpinn .7s linear infinite;
}
@keyframes cdSpinn { to { transform: rotate(360deg); } }

/* Empty state */
.cd-emptyy {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 48px 20px;
    text-align: center;
}
.cd-empty-iconn {
    width: 72px;
    height: 72px;
    background: var(--gray-50);
    border: 1.5px solid var(--border);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 4px;
}
.cd-emptyy h4 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text);
}
.cd-emptyy p {
    font-size: .85rem;
    color: var(--gray-500);
    line-height: 1.5;
}
.cd-browse-btnn {
    display: inline-block;
    margin-top: 6px;
    background: var(--green);
    color: white;
    text-decoration: none;
    padding: 10px 22px;
    border-radius: 10px;
    font-size: .85rem;
    font-weight: 600;
    transition: background .2s;
}
.cd-browse-btnn:hover { background: var(--green-mid); }

/* Cart item rows - STACKED VERTICALLY */
#cdItemss {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.cd-itemm {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px;
    background: var(--white);
    border: 1.5px solid var(--border);
    border-radius: 12px;
    transition: border-color .15s;
    animation: cdItemInn .2s ease both;
    width: 100%;
    box-sizing: border-box;
}
.cd-itemm:hover { 
    border-color: #bbf7d0; 
    background: #fefefe;
}
@keyframes cdItemInn {
    from { opacity: 0; transform: translateY(6px); }
    to   { opacity: 1; transform: translateY(0); }
}
.cd-item-iconn {
    width: 42px;
    height: 42px;
    background: #f0fdf4;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}
.cd-item-infoo {
    flex: 1;
    min-width: 0;
}
.cd-item-codee {
    font-size: 10px;
    font-weight: 700;
    color: var(--green);
    text-transform: uppercase;
    letter-spacing: .07em;
}
.cd-item-titlee {
    font-size: .88rem;
    font-weight: 600;
    color: var(--text);
    margin-top: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.cd-item-qtyy {
    font-size: .75rem;
    color: var(--gray-500);
    margin-top: 2px;
}
.cd-item-pricee {
    font-size: .9rem;
    font-weight: 700;
    color: var(--green);
    flex-shrink: 0;
}

/* Footer */
.cd-footerr {
    padding: 16px 20px 24px;
    border-top: 1.5px solid var(--border);
    flex-shrink: 0;
    background: var(--white);
}
.cd-total-roww {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
}
.cd-total-labell {
    font-size: .82rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: .07em;
}
.cd-total-amountt {
    font-size: 1.4rem;
    font-weight: 800;
    font-family: 'Playfair Display', serif;
    color: var(--green);
}
.cd-checkout-btnn {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    background: var(--green);
    color: white;
    text-decoration: none;
    padding: 13px 16px;
    border-radius: 12px;
    font-size: .9rem;
    font-weight: 700;
    margin-bottom: 10px;
    border: none;
    cursor: pointer;
    transition: background .2s, transform .15s;
}
.cd-checkout-btnn:hover {
    background: var(--green-mid);
    transform: translateY(-1px);
}
.cd-checkout-btnn:active { transform: translateY(0); }
.cd-checkout-btn-leftt {
    display: flex;
    align-items: center;
    gap: 9px;
}
.cd-checkout-iconn {
    width: 28px;
    height: 28px;
    background: rgba(255,255,255,.2);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
}
.cd-continue-btnn {
    width: 100%;
    background: none;
    border: 1.5px solid var(--border);
    color: var(--text);
    padding: 11px;
    border-radius: 12px;
    font-size: .85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all .15s;
}
.cd-continue-btnn:hover {
    border-color: var(--green);
    color: var(--green);
}

@media(max-width: 480px) {
    .cart-drawerr { width: 100vw; border-left: none; }
    .cart-fabb { bottom: 20px; right: 20px; }
}
</style>

<script>
function toggleCartDrawerr() {
    const drawer  = document.getElementById('cartDrawerr');
    const overlay = document.getElementById('cartOverlayy');
    const isOpen  = drawer.classList.contains('open');
    if (isOpen) {
        closeCartDrawerr();
    } else {
        openCartDrawerr();
    }
}

function openCartDrawerr() {
    document.getElementById('cartDrawerr').classList.add('open');
    document.getElementById('cartOverlayy').classList.add('active');
    document.body.style.overflow = 'hidden';
    loadCartItemss();
}

function closeCartDrawerr() {
    document.getElementById('cartDrawerr').classList.remove('open');
    document.getElementById('cartOverlayy').classList.remove('active');
    document.body.style.overflow = '';
}

function getIconn(courseCode) {
    if (!courseCode) return '📚';
    if (courseCode.includes('MTH')) return '📐';
    if (courseCode.includes('LAW')) return '⚖️';
    if (courseCode.includes('BIO')) return '🧬';
    if (courseCode.includes('BUS')) return '💼';
    if (courseCode.includes('CIT')) return '💻';
    return '📚';
}

function loadCartItemss() {
    const loading  = document.getElementById('cdLoadingg');
    const empty    = document.getElementById('cdEmptyy');
    const items    = document.getElementById('cdItemss');
    const footer   = document.getElementById('cdFooterr');
    const countPill = document.getElementById('cdCountPilll');

    loading.style.display = 'flex';
    empty.style.display   = 'none';
    items.style.display   = 'none';
    footer.style.display  = 'none';

    fetch('/cart', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        loading.style.display = 'none';

        if (!data.items || data.items.length === 0) {
            empty.style.display  = 'flex';
            countPill.style.display = 'none';
            return;
        }

        const count = data.items.length;
        countPill.textContent    = count + (count === 1 ? ' item' : ' items');
        countPill.style.display  = 'inline-flex';

        // Each item is now a full-width block stacked vertically
        items.innerHTML = data.items.map(item => `
            <div class="cd-itemm">
                <div class="cd-item-iconn">${getIconn(item.course_code)}</div>
                <div class="cd-item-infoo">
                    <div class="cd-item-codee">${item.course_code}</div>
                    <div class="cd-item-titlee">${escapeHtml(item.title)}</div>
                    <div class="cd-item-qtyy">Quantity: ${item.quantity}</div>
                </div>
                <div class="cd-item-pricee">₦${new Intl.NumberFormat('en-NG').format(item.price * item.quantity)}</div>
            </div>
        `).join('');

        items.style.display  = 'flex';
        items.style.flexDirection = 'column';
        items.style.gap = '12px';
        footer.style.display = 'block';

        const total = data.items.reduce((sum, i) => sum + (i.price * i.quantity), 0);
        document.getElementById('cdTotalAmountt').textContent = '₦' + new Intl.NumberFormat('en-NG').format(total);

        const badge = document.getElementById('cartCountt');
        const totalQty = data.items.reduce((sum, i) => sum + i.quantity, 0);
        badge.textContent    = totalQty;
        badge.style.display  = totalQty > 0 ? 'flex' : 'none';
    })
    .catch(() => {
        loading.style.display = 'none';
        empty.style.display   = 'flex';
    });
}

// Helper function to prevent XSS
function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeCartDrawerr();
});
</script>