<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserCashback extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'amount',
        'type',
        'cashback_date',
    ];

    protected $casts = [
        'amount'        => 'float',
        'cashback_date' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(MockPrice::class, 'plan_id');
    }

    // ── Helpers ────────────────────────────────────────────

    /**
     * Check if user already received cashback today
     */
    public static function receivedToday(int $userId): bool
    {
        return self::where('user_id', $userId)
            ->whereDate('cashback_date', Carbon::today())
            ->exists();
    }

    /**
     * Record a new cashback entry
     */
    public static function record(int $userId, int $planId, float $amount = 5.00, string $type = 'daily_login'): self
    {
        return self::create([
            'user_id'       => $userId,
            'plan_id'       => $planId,
            'amount'        => $amount,
            'type'          => $type,
            'cashback_date' => Carbon::now(),
        ]);
    }

    /**
     * Get total cashback earned by a user
     */
    public static function totalEarned(int $userId): float
    {
        return self::where('user_id', $userId)->sum('amount');
    }

    // ── Scopes ─────────────────────────────────────────────

    public function scopeToday($query)
    {
        return $query->whereDate('cashback_date', Carbon::today());
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}