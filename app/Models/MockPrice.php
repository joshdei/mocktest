<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MockPrice2;
use App\Models\MockpriceColor; // ← add this if missing

class MockPrice extends Model
{
     protected $fillable = [
        'name',
        'price',
        'currency',
        'duration',
        'durationendday',
        'description',
        'status',
        'aistatus',
        'order'
    ];
  protected $attributes = [
        'aistatus' => 0,
    ];    protected $casts = [
        'price'    => 'float',
        'duration' => 'integer',
        'order'    => 'integer',
        'aistatus' => 'integer', // ✅ added
    ];


    // Relationship with MockPrice2
    public function planDetail()
    {
        return $this->hasOne(MockPrice2::class, 'plan_id', 'id');
    }

    // Add this to define available plan types (optional, can be removed)
    public const PLAN_TYPES = [
        'free' => 'Free Plan',
        'basic' => 'Basic Plan',
        'primary' => 'Primary Plan',
        'silver' => 'Silver Plan',
        'gold' => 'Gold Plan',
        'platinum' => 'Platinum Plan',
        'enterprise' => 'Enterprise Plan',
        'premium' => 'Premium Plan',
        'standard' => 'Standard Plan',
    ];

    // Scopes for easy querying
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
public function mockPrice2()
{
    return $this->hasOne(MockPrice2::class, 'plan_id');
}


    public function planColor()
    {
        return $this->hasOne(MockpriceColor::class, 'plan_id', 'id');
    }
     public function userSubscription()
    {
        return $this->hasOne(UserSubscription::class, 'plan_id', 'id');
    }

    
    public function scopeAiActive($query)
    {
        return $query->where('aistatus', 1); // ✅ bonus scope
    }

    public function scopeAiInactive($query)
    {
        return $query->where('aistatus', 0); // ✅ bonus scope
    }
}