@extends('layouts.dashboard')

@section('title')
@section('page-title')

@php
use Illuminate\Support\Str;
@endphp

@section('dashboard-content')
<style>
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

.review-wrapper {
  min-height: 100vh;
  background: var(--off-white);
  padding: 24px;
}

.review-header {
  max-width: 900px;
  margin: 0 auto 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 16px;
}

.review-title {
  font-family: 'Playfair Display', serif;
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--text);
}

.review-stats {
  display: flex;
  gap: 16px;
}

.stat-badge {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  border-radius: 50px;
  font-size: .85rem;
  font-weight: 600;
}

.stat-badge.correct {
  background: var(--green-light);
  color: var(--green);
  border: 1.5px solid rgba(26,107,60,.25);
}

.stat-badge.wrong {
  background: var(--red-light);
  color: var(--red);
  border: 1.5px solid rgba(220,38,38,.25);
}

.review-filters {
  max-width: 900px;
  margin: 0 auto 20px;
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}

.filter-btn {
  padding: 8px 16px;
  border-radius: 8px;
  font-size: .8rem;
  font-weight: 600;
  border: 1.5px solid var(--border);
  background: var(--white);
  color: var(--gray-600);
  cursor: pointer;
  transition: all .18s;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
}

.filter-btn:hover:not(.disabled) {
  border-color: var(--green);
  color: var(--green);
}

.filter-btn.active {
  background: var(--green);
  border-color: var(--green);
  color: #fff;
}

.filter-btn.disabled {
  opacity: 0.4;
  cursor: not-allowed;
  pointer-events: none;
}

.review-list {
  max-width: 900px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.review-card {
  background: var(--white);
  border: 1.5px solid var(--border);
  border-radius: 16px;
  padding: 24px;
  transition: all .18s;
}

.review-card.correct {
  border-left: 4px solid var(--green);
}

.review-card.wrong {
  border-left: 4px solid var(--red);
}

.review-card-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 16px;
  flex-wrap: wrap;
  gap: 10px;
}

.q-number {
  display: flex;
  align-items: center;
  gap: 10px;
}

.q-num-badge {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  display: grid;
  place-items: center;
  font-size: .85rem;
  font-weight: 700;
  color: #fff;
}

.review-card.correct .q-num-badge {
  background: var(--green);
}

.review-card.wrong .q-num-badge {
  background: var(--red);
}

.q-label {
  font-size: .75rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .08em;
  color: var(--text-muted);
}

.q-status {
  padding: 6px 12px;
  border-radius: 50px;
  font-size: .7rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .05em;
}

.review-card.correct .q-status {
  background: var(--green-light);
  color: var(--green);
}

.review-card.wrong .q-status {
  background: var(--red-light);
  color: var(--red);
}

.review-question {
  font-size: 1rem;
  font-weight: 600;
  color: var(--text);
  line-height: 1.6;
  margin-bottom: 20px;
}

.review-options {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-bottom: 16px;
}

.review-option {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 16px;
  border-radius: 10px;
  border: 1.5px solid var(--border);
  background: var(--white);
}

.review-option.selected {
  border-color: var(--amber);
  background: var(--amber-light);
}

.review-option.correct {
  border-color: var(--green);
  background: var(--green-light);
}

.review-option.wrong {
  border-color: var(--red);
  background: var(--red-light);
}

.opt-letter {
  width: 28px;
  height: 28px;
  border-radius: 6px;
  display: grid;
  place-items: center;
  font-size: .8rem;
  font-weight: 700;
  flex-shrink: 0;
}

.review-option .opt-letter {
  background: var(--gray-100);
  color: var(--gray-600);
}

.review-option.selected .opt-letter {
  background: var(--amber);
  color: #fff;
}

.review-option.correct .opt-letter {
  background: var(--green);
  color: #fff;
}

.review-option.wrong .opt-letter {
  background: var(--red);
  color: #fff;
}

.opt-text {
  font-size: .9rem;
  color: var(--gray-700);
}

.review-answers {
  display: flex;
  gap: 20px;
  padding-top: 16px;
  border-top: 1px solid var(--border);
  flex-wrap: wrap;
}

.answer-item {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: .85rem;
}

.answer-label {
  font-weight: 600;
  color: var(--text-muted);
}

.answer-value {
  font-weight: 700;
}

.answer-item.your-answer .answer-value {
  color: var(--text);
}

.answer-item.correct-answer .answer-value {
  color: var(--green);
}

.answer-item.correct-answer.wrong .answer-value {
  color: var(--red);
}

/* Pagination Styles */
.pagination-container {
  max-width: 900px;
  margin: 32px auto 0;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
}

.pagination-btn {
  padding: 8px 14px;
  border-radius: 8px;
  font-size: .85rem;
  font-weight: 600;
  border: 1.5px solid var(--border);
  background: var(--white);
  color: var(--gray-600);
  cursor: pointer;
  transition: all .18s;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 4px;
}

.pagination-btn:hover:not(.active):not(.disabled) {
  border-color: var(--green);
  color: var(--green);
  background: var(--green-pale);
}

.pagination-btn.active {
  background: var(--green);
  border-color: var(--green);
  color: #fff;
}

.pagination-btn.disabled {
  opacity: 0.4;
  cursor: not-allowed;
  pointer-events: none;
}

.pagination-ellipsis {
  color: var(--gray-400);
  padding: 0 4px;
}

.page-info {
  padding: 8px 16px;
  font-size: .85rem;
  color: var(--text-muted);
}

.action-buttons {
  max-width: 900px;
  margin: 24px auto 0;
  display: flex;
  justify-content: center;
  gap: 12px;
  flex-wrap: wrap;
}

.btn-retake {
  padding: 14px 28px;
  border-radius: 12px;
  font-size: .95rem;
  font-weight: 700;
  background: var(--green);
  color: #fff;
  border: none;
  cursor: pointer;
  text-decoration: none;
  transition: all .2s;
  box-shadow: 0 4px 14px var(--shadow-md);
}

.btn-retake:hover {
  background: var(--green-mid);
}

.btn-back {
  padding: 14px 28px;
  border-radius: 12px;
  font-size: .95rem;
  font-weight: 700;
  background: var(--gray-100);
  color: var(--text);
  border: 1.5px solid var(--border);
  cursor: pointer;
  text-decoration: none;
  transition: all .2s;
}

.btn-back:hover {
  background: var(--gray-200);
}

/* AI Explanation Styles */
.ai-button {
  padding: 10px 18px;
  border-radius: 8px;
  font-size: .85rem;
  font-weight: 600;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: #fff;
  border: none;
  cursor: pointer;
  transition: all .18s;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  margin-top: 12px;
}

.ai-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
}

.ai-button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  pointer-events: none;
}

.ai-button.loading {
  opacity: 0.7;
  pointer-events: none;
}

.ai-button.loading::after {
  content: '';
  display: inline-block;
  width: 12px;
  height: 12px;
  margin-left: 6px;
  border: 2px solid rgba(255,255,255,.3);
  border-radius: 50%;
  border-top-color: #fff;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.ai-explanation {
  margin-top: 16px;
  padding: 16px;
  background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
  border: 1.5px solid rgba(102, 126, 234, 0.3);
  border-radius: 10px;
  max-height: 0;
  opacity: 0;
  overflow: hidden;
  transition: all .3s ease;
}

.ai-explanation.show {
  max-height: 800px;
  opacity: 1;
}

.ai-explanation-header {
  font-weight: 700;
  color: #667eea;
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 12px;
}

.ai-explanation-content {
  font-size: .9rem;
  color: var(--text-muted);
  line-height: 1.6;
  white-space: pre-wrap;
  word-wrap: break-word;
}

.ai-error {
  color: var(--red);
  padding: 12px;
  background: var(--red-light);
  border-radius: 8px;
  margin-top: 12px;
}

@media(max-width: 479px) {
  .review-header { flex-direction: column; align-items: flex-start; }
  .review-filters { flex-wrap: wrap; }
  .review-answers { flex-direction: column; gap: 10px; }
  .pagination-btn { padding: 6px 10px; font-size: .75rem; }
  .ai-button { width: 100%; justify-content: center; }
}
</style>

<div class="review-wrapper">
  <div class="review-header">
    <h1 class="review-title">📋 Review Your Answers</h1>
    <div class="review-stats">
      <div class="stat-badge correct">✓ {{ $correctAnswers }} Correct</div>
      <div class="stat-badge wrong">✗ {{ $totalQuestions - $correctAnswers }} Wrong</div>
    </div>
  </div>

  <div class="review-filters">
    <a href="{{ request()->url() }}?filter=all&page={{ $currentPage }}" class="filter-btn {{ request()->get('filter', 'all') === 'all' ? 'active' : '' }}">All Questions</a>
    <a href="{{ request()->url() }}?filter=correct&page={{ $currentPage }}" class="filter-btn {{ request()->get('filter') === 'correct' ? 'active' : '' }}">✓ Correct Only</a>
    <a href="{{ request()->url() }}?filter=wrong&page={{ $currentPage }}" class="filter-btn {{ request()->get('filter') === 'wrong' ? 'active' : '' }}">✗ Wrong Only</a>
  </div>

  <div class="review-list">
    @forelse($paginatedResults as $result)
    <div class="review-card {{ $result['is_correct'] ? 'correct' : 'wrong' }}">
      <div class="review-card-header">
        <div class="q-number">
          <div class="q-num-badge">{{ $result['index'] + 1 }}</div>
          <div class="q-label">Question {{ $result['index'] + 1 }}</div>
        </div>
        <div class="q-status">{{ $result['is_correct'] ? '✓ Correct' : '✗ Wrong' }}</div>
      </div>
      
      <div class="review-question">
        {{ is_array($result['question']) ? ($result['question']['question'] ?? $result['question']['question_text'] ?? 'N/A') : ($result['question']->question ?? 'N/A') }}
      </div>
      
      @if((is_array($result['question']) ? ($result['question']['question_type'] ?? '') : ($result['question']->question_type ?? '')) === 'mcq')
      <div class="review-options">
        @foreach(['a', 'b', 'c', 'd'] as $opt)
          @php
            $questionData = $result['question'];
            $optionKey = 'option_' . $opt;
            $optionValue = is_array($questionData) ? ($questionData[$optionKey] ?? null) : ($questionData->$optionKey ?? null);
            $userAnswer = is_array($result['user_answer']) ? ($result['user_answer']['selected'] ?? '') : $result['user_answer'];
            $correctAnswer = $result['correct_answer'];
          @endphp
          @if($optionValue)
          <div class="review-option 
            {{ strtoupper($userAnswer) === strtoupper($opt) ? 'selected' : '' }}
            {{ strtoupper($correctAnswer) === strtoupper($opt) ? 'correct' : '' }}
            {{ strtoupper($userAnswer) === strtoupper($opt) && !$result['is_correct'] && strtoupper($correctAnswer) !== strtoupper($opt) ? 'wrong' : '' }}">
            <span class="opt-letter">{{ strtoupper($opt) }}</span>
            <span class="opt-text">{{ $optionValue }}</span>
          </div>
          @endif
        @endforeach
      </div>
      @endif
      
      <div class="review-answers">
        <div class="answer-item your-answer">
          <span class="answer-label">Your Answer:</span>
          <span class="answer-value">
            @php
              $userAnswer = $result['user_answer'] ?? '(No answer)';
              if (is_array($userAnswer)) {
                  echo $userAnswer['selected'] ?? '(No answer)';
              } else {
                  echo $userAnswer;
              }
            @endphp
          </span>
        </div>
        <div class="answer-item correct-answer {{ !$result['is_correct'] ? 'wrong' : '' }}">
          <span class="answer-label">Correct Answer:</span>
          <span class="answer-value">
            @php
              $correctAnswer = $result['correct_answer'];
              if (is_array($correctAnswer)) {
                  echo implode(', ', $correctAnswer);
              } else {
                  echo $correctAnswer;
              }
            @endphp
          </span>
        </div>
      </div>

      @php
        $questionObj = $result['question'];
        $questionId = is_array($questionObj) ? ($questionObj['id'] ?? null) : ($questionObj->id ?? null);
        $userAns = is_array($result['user_answer']) ? ($result['user_answer']['selected'] ?? '') : $result['user_answer'];
        $correctAns = $result['correct_answer'];
      @endphp

      {{-- AI Explanation Button --}}
      @if($hasAiAccess && $questionId)
      <button class="ai-button" onclick="toggleAiExplanation(this, {{ $questionId }}, '{{ $userAns }}', '{{ $correctAns }}')">
        🤖 AI Explanation
      </button>
      
      <div class="ai-explanation" id="ai-explanation-{{ $questionId }}">
        <div class="ai-explanation-header">💡 AI Tutor Explanation</div>
        <div class="ai-explanation-content" id="ai-content-{{ $questionId }}">Loading explanation...</div>
      </div>
      @endif
    </div>
    @empty
    <div class="review-card" style="text-align: center; padding: 40px;">
      <p>No questions found with the selected filter.</p>
    </div>
    @endforelse
  </div>

{{-- In the review.blade.php, update the page info section --}}
@if($totalPages > 1)
<div class="pagination-container">
    {{-- Previous Button --}}
    @if($prevPageUrl)
      <a href="{{ $prevPageUrl . (request()->get('filter') ? '&filter=' . request()->get('filter') : '') }}" class="pagination-btn">
        ← Previous Question
      </a>
    @else
      <span class="pagination-btn disabled">← Previous Question</span>
    @endif
    
    {{-- Page Numbers --}}
    @php
      $start = max(1, $currentPage - 2);
      $end = min($totalPages, $currentPage + 2);
      
      if ($start > 1) {
          echo '<a href="' . request()->url() . '?page=1' . (request()->get('filter') ? '&filter=' . request()->get('filter') : '') . '" class="pagination-btn">1</a>';
          if ($start > 2) echo '<span class="pagination-ellipsis">...</span>';
      }
      
      for ($i = $start; $i <= $end; $i++) {
          $isActive = $i === $currentPage;
          $url = request()->url() . '?page=' . $i . (request()->get('filter') ? '&filter=' . request()->get('filter') : '');
          if ($isActive) {
              echo '<span class="pagination-btn active">' . $i . '</span>';
          } else {
              echo '<a href="' . $url . '" class="pagination-btn">' . $i . '</a>';
          }
      }
      
      if ($end < $totalPages) {
          if ($end < $totalPages - 1) echo '<span class="pagination-ellipsis">...</span>';
          echo '<a href="' . request()->url() . '?page=' . $totalPages . (request()->get('filter') ? '&filter=' . request()->get('filter') : '') . '" class="pagination-btn">' . $totalPages . '</a>';
      }
    @endphp
    
    {{-- Next Button --}}
    @if($nextPageUrl)
      <a href="{{ $nextPageUrl . (request()->get('filter') ? '&filter=' . request()->get('filter') : '') }}" class="pagination-btn">
        Next Question →
      </a>
    @else
      <span class="pagination-btn disabled">Next Question →</span>
    @endif
</div>

<div class="page-info" style="text-align: center; max-width: 900px; margin: 12px auto 0;">
    Question {{ $currentPage }} of {{ $totalPages }}
    @if($filter !== 'all')
        <br><small>(Showing {{ $filter === 'correct' ? 'correct' : 'wrong' }} questions only)</small>
    @endif
</div>
@endif

  <div class="action-buttons">
    <a href="{{ route('mock.index') }}" class="btn-retake">🔄 Retake Exam</a>
    <a href="{{ route('dashboard') }}" class="btn-back">← Back to Dashboard</a>
  </div>
</div>

<script>
  function toggleAiExplanation(button, questionId, userAnswer, correctAnswer) {
    const explanationDiv = document.getElementById(`ai-explanation-${questionId}`);
    const contentDiv = document.getElementById(`ai-content-${questionId}`);
    
    // If already showing, hide it
    if (explanationDiv.classList.contains('show')) {
      explanationDiv.classList.remove('show');
      button.classList.remove('loading');
      return;
    }
    
    // Add loading state
    button.classList.add('loading');
    button.disabled = true;
    
    // Get CSRF token with fallback to Blade helper
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
    
    // Fetch AI explanation
    fetch('{{ route("mock.ai-explain") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
      },
      body: JSON.stringify({
        question_id: questionId,
        user_answer: userAnswer,
        correct_answer: correctAnswer
      })
    })
    .then(response => {
      if (!response.ok) {
        throw new Error(`Server error: ${response.status} ${response.statusText}`);
      }
      return response.text();
    })
    .then(text => {
      try {
        const data = JSON.parse(text);
        button.classList.remove('loading');
        button.disabled = false;
        
        if (data.error) {
          contentDiv.innerHTML = `<div class="ai-error">❌ ${data.error}</div>`;
        } else {
          contentDiv.innerHTML = data.explanation;
        }
        
        explanationDiv.classList.add('show');
      } catch (e) {
        throw new Error(`Invalid JSON response: ${text.substring(0, 100)}`);
      }
    })
    .catch(error => {
      button.classList.remove('loading');
      button.disabled = false;
      console.error('AI Explanation Error:', error);
      contentDiv.innerHTML = `<div class="ai-error">❌ Error: ${error.message}</div>`;
      explanationDiv.classList.add('show');
    });
  }
</script>
@endsection