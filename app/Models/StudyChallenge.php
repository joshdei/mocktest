<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudyChallenge extends Model
{
    protected $fillable = [
        'challenger_id',
        'opponent_id',
        'question_set',
        'challenger_score',
        'opponent_score',
        'status',
        'expires_at',
        'winner_id',
    ];

    protected $casts = [
        'question_set' => 'array',
        'expires_at' => 'datetime',
        'challenger_score' => 'integer',
        'opponent_score' => 'integer',
    ];

    public function challenger(): BelongsTo
    {
        return $this->belongsTo(User::class, 'challenger_id');
    }

    public function opponent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'opponent_id');
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_id');
    }
}
