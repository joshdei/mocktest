<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class Test extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'score',
        'total_questions',
        'cooldown_expires_at',
    ];

    protected $dates = ['cooldown_expires_at'];

    /**
     * Get the answers for the test.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(TestAnswer::class);
    }

    /**
     * Get the user who took the test.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the course this test is related to.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the test query details.
     */
    public function testQuery(): HasOne
    {
        return $this->hasOne(TestQuery2::class);
    }

    /**
     * Calculate percentage score.
     */
    public function percentageScore(): float
    {
        if ($this->total_questions == 0) {
            return 0;
        }
        return ($this->score / $this->total_questions) * 100;
    }

    /**
     * Check if user can retake the test (cooldown period passed).
     */
    public function canRetake(): bool
    {
        // If no cooldown expiration is set, they can retake immediately
        if (!$this->cooldown_expires_at) {
            return true;
        }
        
        // Check if current time is after cooldown expiration
        return now()->greaterThan($this->cooldown_expires_at);
    }

    /**
     * Get remaining cooldown time in minutes.
     */
    public function cooldownRemainingMinutes(): int
    {
        if (!$this->cooldown_expires_at) {
            return 0;
        }
        
        // If cooldown has already passed, return 0
        if (now()->greaterThan($this->cooldown_expires_at)) {
            return 0;
        }
        
        // Calculate remaining minutes
        return now()->diffInMinutes($this->cooldown_expires_at);
    }

    /**
     * Check if test is completed.
     */
    public function isCompleted(): bool
    {
        return $this->score !== null && $this->total_questions > 0;
    }

    /**
     * Check if test is in progress.
     */
    public function isInProgress(): bool
    {
        return $this->score === null || $this->total_questions == 0;
    }

    /**
     * Get formatted percentage.
     */
    public function getFormattedPercentageAttribute(): string
    {
        return number_format($this->percentageScore(), 2) . '%';
    }
}