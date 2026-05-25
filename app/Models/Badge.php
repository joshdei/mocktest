<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Badge extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'icon',
        'color',
        'description',
        'type',
        'plan_type',
        'required_tests',
        'required_score',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'required_tests' => 'integer',
        'required_score' => 'integer',
        'order' => 'integer'
    ];

    /**
     * Users who have earned this badge
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_badges')
                    ->withPivot('earned_at', 'metadata')
                    ->withTimestamps();
    }

    /**
     * Scope for active badges
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for plan-specific badges
     */
    public function scopeForPlan($query, $planType)
    {
        return $query->where('type', 'plan')
                     ->where('plan_type', $planType);
    }

    /**
     * Check if badge is for Gold plan
     */
    public function isGoldPlanBadge()
    {
        return $this->type === 'plan' && strtolower($this->plan_type) === 'gold';
    }

    /**
     * Get badge display HTML
     */
    public function getDisplayHtml()
    {
        $icon = $this->icon ? "<i class='{$this->icon}'></i> " : '';
        return "<span class='badge' style='background-color: {$this->color}; color: white; padding: 5px 10px; border-radius: 20px;'>
                {$icon}{$this->name}
                </span>";
    }
}