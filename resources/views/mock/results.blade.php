@extends('layouts.dashboard')

@section('title')
@section('page-title')

@section('dashboard-content')
<style>
  
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
:root {
  --green:       #1A6B3C;
  --green-mid:   #22844B;
  --green-light: #E8F5EE;
  --green-pale:  #F0FAF4;
  --white:       #FFFFFF;
  --off-white:   #F8F9F6;
  --gray-50:     #F9FAFB;
  --gray-100:    #F3F4F6;
  --gray-200:    #E5E7EB;
  --gray-300:    #D1D5DB;
  --gray-400:    #9CA3AF;
  --gray-500:    #6B7280;
  --gray-600:    #4B5563;
  --gray-700:    #374151;
  --text:        #1C2B1E;
  --text-muted:  #5A6B5E;
  --border:      #DDE8E1;
  --amber:       #D97706;
  --amber-light: #FEF3C7;
  --red:         #DC2626;
  --red-light:   #FEF2F2;
  --shadow:      rgba(26,107,60,.08);
  --shadow-md:   rgba(26,107,60,.14);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.results-wrapper {
  min-height: 100vh;
  background: var(--off-white);
  padding: 24px;
}

.results-header {
  max-width: 900px;
  margin: 0 auto 24px;
}

.results-title {
  font-family: 'Playfair Display', serif;
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--text);
  margin-bottom: 8px;
}

.results-subtitle {
  font-size: .93rem;
  color: var(--text-muted);
}

.results-list {
  max-width: 900px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.result-card {
  background: var(--white);
  border: 1.5px solid var(--border);
  border-radius: 16px;
  padding: 24px;
  display: flex;
  align-items: center;
  gap: 20px;
  transition: all .18s;
}

.result-card:hover {
  border-color: var(--green);
  box-shadow: 0 4px 16px var(--shadow);
}

.rc-course {
  flex: 1;
}

.rc-course-code {
  font-size: .75rem;
  font-weight: 700;
  color: var(--green);
  text-transform: uppercase;
  letter-spacing: .05em;
  margin-bottom: 4px;
}

.rc-course-name {
  font-size: 1rem;
  font-weight: 600;
  color: var(--text);
}

.rc-date {
  font-size: .8rem;
  color: var(--text-muted);
}

.rc-score {
  display: flex;
  flex-direction: column;
  align-items: center;
  min-width: 80px;
}

.rc-score-value {
  font-family: 'Playfair Display', serif;
  font-size: 1.75rem;
  font-weight: 800;
}

.rc-score-value.pass {
  color: var(--green);
}

.rc-score-value.fail {
  color: var(--red);
}

.rc-score-label {
  font-size: .65rem;
  color: var(--text-muted);
  text-transform: uppercase;
  letter-spacing: .05em;
}

.rc-status {
  padding: 6px 12px;
  border-radius: 50px;
  font-size: .7rem;
  font-weight: 700;
  text-transform: uppercase;
}

.rc-status.pass {
  background: var(--green-light);
  color: var(--green);
}

.rc-status.fail {
  background: var(--red-light);
  color: var(--red);
}

.rc-btn {
  padding: 10px 20px;
  border-radius: 10px;
  font-size: .85rem;
  font-weight: 700;
  text-decoration: none;
  transition: all .2s;
  text-align: center;
}

.rc-btn-review {
  background: var(--green-light);
  color: var(--green);
  border: 1.5px solid rgba(26,107,60,.25);
}

.rc-btn-review:hover {
  background: var(--green);
  color: #fff;
}

.rc-btn-retake {
  background: var(--green);
  color: #fff;
}

.rc-btn-retake:hover {
  background: var(--green-mid);
}

.empty-state {
  max-width: 900px;
  margin: 60px auto;
  text-align: center;
  padding: 60px 20px;
}

.empty-icon {
  font-size: 4rem;
  margin-bottom: 16px;
}

.empty-title {
  font-family: 'Playfair Display', serif;
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--text);
  margin-bottom: 8px;
}

.empty-text {
  color: var(--text-muted);
  margin-bottom: 24px;
}

.empty-btn {
  display: inline-block;
  padding: 14px 28px;
  background: var(--green);
  color: #fff;
  border-radius: 12px;
  font-weight: 700;
  text-decoration: none;
  transition: all .2s;
}

.empty-btn:hover {
  background: var(--green-mid);
}

@media(max-width: 600px) {
  .result-card {
    flex-direction: column;
    align-items: flex-start;
  }
  .rc-score {
    flex-direction: row;
    gap: 12px;
    width: 100%;
    justify-content: space-between;
  }
  .rc-btn {
    width: 100%;
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

}
</style>

<div class="results-wrapper">
  {{-- <div class="results-header">
    <h1 class="results-title">📊 Your Exam Results</h1>
    <p class="results-subtitle">View all your past exam attempts and review your answers</p>
  </div> --}}

@if(!isset($tests) || $tests->isEmpty())
  <div class="empty-state">
    <div class="empty-icon">📝</div>
    <h2 class="empty-title">No Exams Taken Yet</h2>
    <p class="empty-text">You haven't taken any mock exams yet. Start a practice exam to see your results here.</p>
    <a href="{{ route('mock.index') }}" class="empty-btn">📥 Start Exam</a>
  </div>
  @else
  <div class="results-list">
    @foreach($tests as $test)
    <div class="result-card">
      <div class="rc-course">
        <div class="rc-course-code">{{ $test->course->course_code ?? 'N/A' }}</div>
        <div class="rc-course-name">{{ $test->course->course_name ?? 'Unknown Course' }}</div>
        <div class="rc-date">{{ $test->created_at->format('M d, Y \a\t h:i A') }}</div>
      </div>
      
      <div class="rc-score">
        <span class="rc-score-value {{ $test->score >= ($test->total_questions * 0.5) ? 'pass' : 'fail' }}">
          {{ round(($test->score / $test->total_questions) * 100) }}%
        </span>
        <span class="rc-score-label">Score</span>
      </div>
      
      <span class="rc-status {{ $test->score >= ($test->total_questions * 0.5) ? 'pass' : 'fail' }}">
        {{ $test->score >= ($test->total_questions * 0.5) ? '✓ Pass' : '✗ Fail' }}
      </span>
      
      <a href="{{ route('mock.review') }}?test_id={{ $test->id }}" class="rc-btn rc-btn-review">
        📋 Review
      </a>
      
      <a href="{{ route('mock.setup', $test->course_id) }}" class="rc-btn rc-btn-retake">
        🔄 Retake
      </a>
    </div>
    @endforeach
  </div>
  
  {{-- Pagination --}}
  @if($tests->hasPages())
  <div class="pagination" style="display:flex;justify-content:center;gap:8px;margin-top:32px;">
    @if($tests->onFirstPage())
    <span class="pagination-btn" style="opacity:0.4;pointer-events:none;">← Previous</span>
    @else
    <a href="{{ $tests->previousPageUrl() }}" class="pagination-btn">← Previous</a>
    @endif
    
    @foreach($tests->getUrlRange(1, $tests->lastPage()) as $page => $url)
      @if($page == $tests->currentPage())
      <span class="pagination-btn active">{{ $page }}</span>
      @else
      <a href="{{ $url }}" class="pagination-btn">{{ $page }}</a>
      @endif
    @endforeach
    
    @if($tests->hasMorePages())
    <a href="{{ $tests->nextPageUrl() }}" class="pagination-btn">Next →</a>
    @else
    <span class="pagination-btn" style="opacity:0.4;pointer-events:none;">Next →</span>
    @endif
  </div>
  @endif
  @endif
</div>
@endsection
