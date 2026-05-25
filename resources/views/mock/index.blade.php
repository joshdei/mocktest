@extends('layouts.dashboard')

@section('title')
@section('page-title')

@section('dashboard-content')
<style>
  
/* Pagination card wrapper */
.pagination-card {
  width: 100%;
  max-width: 720px;
  margin: 0 auto;
  padding: 14px;
  border-radius: 14px;
  border: 1.5px solid var(--border);
  background: var(--white);
  box-shadow: 0 8px 28px rgba(26,107,60,.08);
}
.pagination-inner {
  display: flex;
  flex-direction: column;
  gap: 10px;
  align-items: center;
}
.pagination-label {
  font-family: 'Playfair Display', serif;
  font-weight: 800;
  color: var(--text);
  font-size: .95rem;
}

/* Remove default list spacing from bootstrap pagination */
.pagination-card .pagination {
  margin: 0 !important;
}

.pagination-card .page-item:first-child .page-link,
.pagination-card .page-item:last-child .page-link {
  border-radius: 10px;
}

.pagination-card .page-link {
  background: var(--white);
  color: var(--text);
  border: 1.5px solid var(--border);
  font-weight: 700;
  padding: 8px 14px;
  transition: all .18s;
}

.pagination-card .page-link:hover {
  background: #EFF6FF;
  border-color: rgba(37,99,235,.25);
}

.pagination-card .page-item.active .page-link {
  background: #2563EB !important;
  border-color: #2563EB !important;
  color: #fff !important;
}

.pagination-card .page-item.disabled .page-link {
  opacity: .5;
  cursor: not-allowed;
}

/* keep layout safe on small screens */
@media (max-width: 480px) {
  .pagination-card { padding: 12px; }
  .pagination-card .pagination { gap: 6px; }
}

.exam-header {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
}
.exam-header h2 {
  font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 800; color: var(--text);
}
.exam-count { font-size: .85rem; color: var(--text-muted); }

.exam-search {
  margin-bottom: 20px;
}
.search-wrap {
  display: flex; align-items: center; gap: 8px;
  background: var(--gray-50); border: 1.5px solid var(--border);
  border-radius: 10px; padding: 8px 14px; max-width: 400px;
}
.search-wrap input {
  background: none; border: none; outline: none;
  font-family: 'DM Sans', sans-serif; font-size: .85rem;
  color: var(--text); flex: 1; min-width: 150px;
}
.search-wrap input::placeholder { color: var(--gray-400); }
.search-wrap .si { color: var(--gray-400); font-size: .95rem; }
.btn-search {
  padding: 8px 16px; border-radius: 8px; font-size: .82rem;
  font-weight: 700; font-family: 'DM Sans', sans-serif; cursor: pointer;
  border: none; background: var(--green); color: #fff;
  transition: all .18s;
}
.btn-search:hover { background: var(--green-mid); }

.exam-grid {
  display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 18px; margin-bottom: 24px;
}
.exam-card {
  background: var(--white); border: 1.5px solid var(--border);
  border-radius: var(--r-lg); padding: 20px;
  transition: all .2s; display: flex; flex-direction: column;
}
.exam-card:hover {
  box-shadow: 0 8px 28px var(--shadow); transform: translateY(-3px);
  border-color: rgba(26,107,60,.25);
}
.exam-icon {
  width: 48px; height: 48px; background: var(--green-light);
  border-radius: 12px; display: grid; place-items: center;
  font-size: 1.6rem; margin-bottom: 14px;
}
.exam-code {
  font-size: .72rem; font-weight: 700; color: var(--green);
  text-transform: uppercase; letter-spacing: .06em; margin-bottom: 4px;
}
.exam-name {
  font-family: 'Playfair Display', serif; font-size: 1.05rem;
  font-weight: 700; color: var(--text); line-height: 1.3; margin-bottom: 10px;
}
.exam-meta {
  font-size: .78rem; color: var(--text-muted); margin-bottom: 14px;
}
.exam-actions {
  margin-top: auto; display: flex; gap: 8px;
}
.btn-exam {
  flex: 1; padding: 10px; border-radius: 10px; font-size: .82rem;
  font-weight: 700; font-family: 'DM Sans', sans-serif; cursor: pointer;
  border: none; text-align: center; text-decoration: none; transition: all .18s;
}
.btn-exam.primary {
  background: var(--green); color: #fff;
  box-shadow: 0 3px 12px rgba(26,107,60,.25);
}
.btn-exam.primary:hover { background: var(--green-mid); }
.btn-exam.secondary {
  background: var(--gray-50); color: var(--text);
  border: 1.5px solid var(--border);
}
.btn-exam.secondary:hover { background: var(--green-pale); border-color: rgba(26,107,60,.2); }

.empty-exam {
  display: flex; flex-direction: column; align-items: center;
  justify-content: center; padding: 60px 20px; gap: 12px; color: var(--gray-400);
}

.pagination-wrap { display: flex; justify-content: center; margin-top: 16px; }

.pagination-wrap { display: flex; justify-content: center; margin-top: 8px; }
.pagination-wrap .pagination {
  display: flex; flex-wrap: wrap; gap: 6px; list-style: none; padding: 0; margin: 0;
  justify-content: center; align-items: center;
}
.pagination-wrap .pagination li a,
.pagination-wrap .pagination li span {
  padding: 8px 14px; border-radius: 8px; font-size: .8rem; font-weight: 600;
  border: 1.5px solid var(--border); background: var(--white); color: var(--text);
  text-decoration: none; transition: all .18s; display: inline-block;
}
.pagination-wrap .pagination li a:hover { background: #EFF6FF; border-color: rgba(37,99,235,.25); }
.pagination-wrap .pagination li.active span {
  background: #2563EB; color: #fff; border-color: #2563EB;
}
.pagination-wrap .pagination li.disabled span {
  opacity: .5; cursor: not-allowed;
}
.pagination-wrap .pagination li { display: inline-block; }
.back-link {
  display: inline-flex; align-items: center; gap: 6px;
  font-size: .85rem; color: var(--green); font-weight: 600;
  text-decoration: none; margin-bottom: 16px;
}
.back-link:hover { text-decoration: underline; }

.empty-exam .em-icon { font-size: 3rem; }
.empty-exam p { font-size: .9rem; color: var(--gray-500); text-align: center; }

.pag-wrap {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
  margin-top: 28px;
  margin-bottom: 8px;
}

.pag-box {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 6px;
  justify-content: center;
}

.pag-btn {
  height: 38px;
  min-width: 38px;
  padding: 0 12px;
  border-radius: 10px;
  border: 1.5px solid var(--border);
  background: var(--white);
  color: var(--text);
  font-size: .82rem;
  font-weight: 600;
  font-family: 'DM Sans', sans-serif;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  transition: all .18s;
}

.pag-btn:hover {
  background: #EFF6FF;
  border-color: rgba(37,99,235,.3);
  color: #2563EB;
}

.pag-btn.active {
  background: var(--green);
  color: #fff;
  border-color: var(--green);
  box-shadow: 0 3px 10px rgba(26,107,60,.25);
}

.pag-btn.disabled {
  opacity: .4;
  cursor: not-allowed;
  pointer-events: none;
}

.pag-btn.pag-nav {
  padding: 0 16px;
  min-width: unset;
}

.pag-ellipsis {
  height: 38px;
  width: 32px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: .85rem;
  color: var(--text-muted);
}

.pag-info {
  font-size: .78rem;
  color: var(--text-muted);
  font-family: 'DM Sans', sans-serif;
}

@media (max-width: 480px) {
  .pag-btn { height: 34px; min-width: 34px; font-size: .78rem; }
  .pag-btn.pag-nav { padding: 0 12px; }
}
</style>



<form method="GET" action="{{ route('mock.index') }}" class="exam-search">
  <div class="search-wrap">
    <span class="si">🔍</span>
    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by course code or name…"/>
    <button type="submit" class="btn-search">Search</button>
  </div>
</form>

@if($courses->count())
<div class="exam-grid">
  @foreach($courses as $course)
  <div class="exam-card">
    <div class="exam-icon">📝</div>
    <div class="exam-code">{{ $course->course_code }}</div>
    <div class="exam-name">{{ $course->course_name }}</div>

<div class="exam-actions">
  @if(($course->questions_count ?? $course->questions->count()) > 0)
    <a href="{{ route('mock.setup', $course->id) }}" class="btn-exam primary">
       Start Exam
    </a>
  @else
    <button class="btn-exam primary" disabled style="opacity:.45; cursor:not-allowed; box-shadow:none;">
     No Questions
    </button>
  @endif
</div>
  </div>
  @endforeach
</div>
 {{-- PAGINATION --}}
@if($courses->hasPages())
<div class="pag-wrap">
  <div class="pag-box">

    {{-- Prev --}}
    @if($courses->onFirstPage())
      <span class="pag-btn pag-nav disabled">← Prev</span>
    @else
      <a href="{{ $courses->appends(['search'=>$search])->previousPageUrl() }}" class="pag-btn pag-nav">← Prev</a>
    @endif

    {{-- Page Numbers --}}
    @foreach($courses->getUrlRange(1, $courses->lastPage()) as $page => $url)
      @if($page == $courses->currentPage())
        <span class="pag-btn active">{{ $page }}</span>
      @elseif($page == 1 || $page == $courses->lastPage() || abs($page - $courses->currentPage()) <= 1)
        <a href="{{ $courses->appends(['search'=>$search])->url($page) }}" class="pag-btn">{{ $page }}</a>
      @elseif(abs($page - $courses->currentPage()) == 2)
        <span class="pag-ellipsis">…</span>
      @endif
    @endforeach

    {{-- Next --}}
    @if($courses->hasMorePages())
      <a href="{{ $courses->appends(['search'=>$search])->nextPageUrl() }}" class="pag-btn pag-nav">Next →</a>
    @else
      <span class="pag-btn pag-nav disabled">Next →</span>
    @endif

  </div>

  <p class="pag-info">
    Showing {{ $courses->firstItem() }}–{{ $courses->lastItem() }} of {{ $courses->total() }} courses
  </p>
</div>
@endif
@else
<div class="empty-exam">
  <span class="em-icon">📭</span>
  <p>No courses found{{ $search ? ' matching "' . $search . '"' : '' }}.</p>
  @if($search)
  <a href="{{ route('mock.index') }}" class="btn-exam secondary" style="text-decoration:none;display:inline-block;">Clear Search</a>
  @endif
</div>
@endif
@endsection
