@extends('layouts.dashboard')

@section('title')
@section('page-title')
@endsection

@section('dashboard-content')
<style>
:root {
  --green:       #1A6B3C;
  --green-light: #E8F5EE;
  --border:      #DDE8E1;
  --text:        #1C2B1E;
  --gray-500:    #6B7280;
  --gray-700:    #374151;
  --amber:       #D97706;
  --red:         #DC2626;
}
.card {
  background: #fff;
  border: 1.5px solid var(--border);
  border-radius: 16px;
  padding: 22px;
  max-width: 900px;
  margin: 0 auto;
}
.hdr { display:flex; align-items:center; justify-content:space-between; gap: 16px; margin-bottom: 18px; }
.hdr h1 { margin:0; font-family:'Playfair Display',serif; color:var(--text); font-size: 1.25rem; }
.badge {
  padding: 8px 12px;
  border-radius: 999px;
  border: 1.5px solid var(--border);
  font-weight: 800;
  font-family:'DM Sans',sans-serif;
  color: var(--gray-700);
}
.badge.win { background: var(--green-light); color: var(--green); border-color: rgba(26,107,60,.25); }
.badge.lose { background: #FEF2F2; color: var(--red); border-color: rgba(220,38,38,.25); }
.badge.draw { background: #FFFBEB; color: var(--amber); border-color: rgba(217,119,6,.25); }
.vs-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
.vs-table td { padding: 12px 10px; border-bottom: 1px solid var(--border); }
.vs-table tr:last-child td { border-bottom: none; }
.player-name { font-weight: 800; color: var(--gray-700); }
.score { text-align: right; font-weight: 900; color: var(--text); font-size: 1.2rem; }
.actions { display:flex; gap: 10px; margin-top: 18px; flex-wrap: wrap; }
.btn {
  padding: 10px 16px;
  border-radius: 10px;
  text-decoration: none;
  font-weight: 800;
  font-family:'DM Sans',sans-serif;
  border: 1.5px solid var(--border);
  background: #fff;
  color: var(--gray-700);
  cursor: pointer;
}
.btn.primary { background: var(--green); border-color: var(--green); color: #fff; }
</style>

@php
  $user = auth()->user();
  $challenger = $challenge->challenger ?? null;
  $opponent = $challenge->opponent ?? null;
  $cScore = (int) ($challenge->challenger_score ?? 0);
  $oScore = (int) ($challenge->opponent_score ?? 0);
  $winnerId = $challenge->winner_id;

  $isDraw = $winnerId === null;
  $myWin = !$isDraw && $winnerId === $user->id;
  $myLose = !$isDraw && $winnerId !== $user->id;

  $resultTitle = $isDraw ? 'It\'s a Draw! 🤝' : ($myWin ? 'You Won! 🏆' : 'You Lost! 😢');
  $badgeClass = $isDraw ? 'draw' : ($myWin ? 'win' : 'lose');
@endphp

<div class="card">
  <div class="hdr">
    <h1>{{ $resultTitle }}</h1>
    <div class="badge {{ $badgeClass }}">{{ $isDraw ? 'Draw' : ($myWin ? 'Winner' : 'Loser') }}</div>
  </div>

  <table class="vs-table">
    <tr>
      <td>
        <div class="player-name">{{ $challenger?->first_name ?? 'Challenger' }} {{ $challenger?->id === $user->id ? '(You)' : '' }}</div>
      </td>
      <td class="score">{{ $cScore }}%</td>
    </tr>
    <tr>
      <td>
        <div class="player-name">{{ $opponent?->first_name ?? 'Opponent' }} {{ $opponent?->id === $user->id ? '(You)' : '' }}</div>
      </td>
      <td class="score">{{ $oScore }}%</td>
    </tr>
  </table>

  <div class="actions">
    <form method="POST" action="{{ route('challenge.send') }}" style="display:inline-block;flex:1;">
      @csrf
      <input type="hidden" name="opponent_id" value="{{ $challenger?->id === $user->id ? ($opponent?->id) : ($challenger?->id) }}">
      <input type="hidden" name="question_set" value="{{ json_encode($challenge->question_set ?? []) }}">
      <input type="hidden" name="original_challenge_id" value="{{ $challenge->id }}">
      <button class="btn primary" type="submit">⚡ Rematch</button>
    </form>
  </div>
</div>
@endsection

