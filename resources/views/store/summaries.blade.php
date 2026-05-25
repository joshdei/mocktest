@extends('layouts.dashboard')

@section('title', 
    request('search') 
        ? strtoupper(request('search')) . ' NOUN Past Questions — Free PDF Download | ' . config('app.name')
        : 'NOUN Past Questions — Free PDF Download | ' . config('app.name')
)

@section('description', 
    request('search')
        ? 'Download free ' . strtoupper(request('search')) . ' NOUN past questions for POP and E-exam. All semesters available in PDF format.'
        : 'Download free NOUN past questions for all courses. POP and E-exam PDFs for all faculties.'
)
@section('page-title', 'Summaries Store')

@section('dashboard-content')

<style>
/* ── Header ── */
.store-header {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 20px; flex-wrap: wrap; gap: 12px;
}
.store-header h2 {
  font-family: 'Playfair Display', serif;
  font-size: 1.3rem; font-weight: 800; color: var(--text); margin-bottom: 4px;
}
.store-count { font-size: .78rem; color: var(--text-muted); font-weight: 500; }

/* ── Back Link ── */
.ws-cta {
  display: inline-flex; align-items: center; gap: 6px;
  font-size: .82rem; font-weight: 600; color: var(--green);
  text-decoration: none; transition: opacity .2s;
}
.ws-cta:hover { opacity: .7; }

/* ── Search ── */
.store-search { margin-bottom: 24px; }
.search-wrap {
  display: flex; align-items: center; gap: 10px;
  background: var(--white); border: 1.5px solid var(--border);
  border-radius: 12px; padding: 6px 14px;
  max-width: 420px; transition: all .2s;
}
.search-wrap:focus-within {
  border-color: var(--green);
  box-shadow: 0 0 0 3px rgba(26,107,60,.1);
}
.search-wrap .si { font-size: 1rem; flex-shrink: 0; }
.search-wrap input {
  flex: 1; border: none; outline: none;
  font-size: .88rem; font-family: 'DM Sans', sans-serif;
  color: var(--text); background: transparent;
}
.search-wrap input::placeholder { color: var(--gray-400); }

/* ── Grid ── */
.store-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 16px; margin-bottom: 24px;
}

/* ── Card ── */
.store-card {
  background: var(--white); border: 1.5px solid var(--border);
  border-radius: 16px; padding: 22px 18px;
  display: flex; flex-direction: column; align-items: center;
  text-align: center; gap: 8px;
  transition: all .2s;
  box-shadow: 0 2px 8px var(--shadow);
}
.store-card:hover {
  border-color: var(--green);
  box-shadow: 0 8px 24px var(--shadow-md);
  transform: translateY(-2px);
}

.store-icon {
  width: 52px; height: 52px; border-radius: 14px;
  background: var(--green-light); border: 1.5px solid rgba(26,107,60,.2);
  display: grid; place-items: center; font-size: 1.5rem;
  margin-bottom: 4px;
}
.store-code {
  font-family: 'Playfair Display', serif;
  font-size: 1rem; font-weight: 800; color: var(--text);
}
.store-meta {
  font-size: .74rem; color: var(--text-muted);
  line-height: 1.4;
}
.store-price {
  font-family: 'Playfair Display', serif;
  font-size: 1.3rem; font-weight: 800; color: var(--green);
  margin-top: 4px;
}

/* ── Buttons ── */
.store-actions { width: 100%; margin-top: 4px; }
.btn-store {
  display: inline-flex; align-items: center; justify-content: center;
  gap: 6px; width: 100%; padding: 10px 14px;
  border-radius: 10px; border: none; cursor: pointer;
  font-family: 'DM Sans', sans-serif; font-size: .82rem;
  font-weight: 700; text-decoration: none; transition: all .2s;
}
.btn-store.primary {
  background: var(--green); color: #fff;
  box-shadow: 0 4px 12px var(--shadow-md);
}
.btn-store.primary:hover {
  background: var(--green-mid); transform: translateY(-1px);
  box-shadow: 0 6px 18px var(--shadow-md);
}

/* ── Pagination ── */
.pagination-wrap {
  display: flex; justify-content: center; margin-bottom: 24px;
}
.pagination-wrap .pagination {
  display: flex; gap: 6px; list-style: none; padding: 0; margin: 0;
}
.pagination-wrap .page-item .page-link {
  display: flex; align-items: center; justify-content: center;
  width: 36px; height: 36px; border-radius: 8px;
  font-size: .82rem; font-weight: 600; color: var(--text);
  border: 1.5px solid var(--border); background: var(--white);
  text-decoration: none; transition: all .2s;
}
.pagination-wrap .page-item .page-link:hover {
  border-color: var(--green); color: var(--green); background: var(--green-pale);
}
.pagination-wrap .page-item.active .page-link {
  background: var(--green); color: #fff; border-color: var(--green);
}
.pagination-wrap .page-item.disabled .page-link {
  opacity: .4; pointer-events: none;
}

/* ── Empty State ── */
.empty-store {
  background: var(--white); border: 1.5px solid var(--border);
  border-radius: 16px; padding: 60px 20px;
  display: flex; flex-direction: column;
  align-items: center; gap: 12px; text-align: center;
}
.empty-store .em-icon { font-size: 2.8rem; }
.empty-store p { font-size: .88rem; color: var(--text-muted); }

/* ── Error Alert ── */
.store-error {
  background: #FEF2F2; border: 1.5px solid rgba(239,68,68,.2);
  color: #B91C1C; padding: 12px 16px; border-radius: 10px;
  margin-bottom: 18px; font-size: .87rem; font-weight: 600;
}

/* ── Mobile ── */
@media(max-width: 640px) {
  .store-grid { grid-template-columns: repeat(2, 1fr); }
  .store-header { flex-direction: column; align-items: flex-start; }
  .search-wrap { max-width: 100%; }
}
</style>



<div class="store-header">
  <div>
    <h2>📄 Summaries Store</h2>
    <div class="store-count">{{ $items->total() }} summary {{ $items->total() == 1 ? 'PDF' : 'PDFs' }} available</div>
  </div>
  <a href="{{ route('dashboard') }}" class="ws-cta">← Back to Dashboard</a>
</div>

<form method="GET" action="{{ route('store.summaries') }}" class="store-search">
  <div class="search-wrap">
    <span class="si">🔍</span>
    <input type="text" name="search" value="{{ request('search') }}" placeholder="FRE423 OR FRE_423"/>
    <button type="submit" class="btn-store primary" style="width:auto;padding:8px 16px;">Search</button>
  </div>
</form>

@if($items->count())
<div class="store-grid">
  @foreach($items as $item)
  <div class="store-card">
    <div class="store-icon">📄</div>
    <div class="store-code">{{ $item->course_code }}</div>
    <div class="store-meta">Condensed notes & key points</div>
    <div class="store-price">₦{{ number_format($item->price ?? 0) }}</div>
    <div class="store-actions">
      <a href="javascript:void(0)" class="btn-store primary add-to-cart"
         data-id="{{ $item->id }}"
         data-code="{{ $item->course_code }}"
         data-price="{{ $item->price ?? 0 }}">
        🛒 Add to Cart
      </a>
    </div>
  </div>
  @endforeach
</div>

<div class="pagination-wrap">
  {{ $items->links('pagination::bootstrap-5') }}
</div>

@else
<div class="empty-store">
  <span class="em-icon">📭</span>
  <p>No summaries available right now. Check back soon!</p>
</div>
@endif

@endsection