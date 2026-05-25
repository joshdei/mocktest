<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MockPrice2 extends Model
{
    protected $table = 'mock_price2s'; // Specify table name if different
    
    protected $fillable = [
        'plan_id',
        'plan_type',
        'number_of_question',
    ];

    protected $casts = [
        'number_of_question' => 'integer',
    ];

    public const PLAN_TYPES = [
        'free'      => 'Free Plan',
        'basic'      => 'Basic Plan',
        'primary'    => 'Primary Plan',
        'silver'     => 'Silver Plan',
        'gold'       => 'Gold Plan',
        'platinum'   => 'Platinum Plan',
        'enterprise' => 'Enterprise Plan',
        'premium'    => 'Premium Plan',
        'standard'   => 'Standard Plan',
    ];

    // Relationship with MockPrice
    public function plan()
    {
        return $this->belongsTo(MockPrice::class, 'plan_id', 'id');
    }

    // Accessor for formatted plan type
    public function getFormattedPlanTypeAttribute()
    {
        return self::PLAN_TYPES[$this->plan_type] ?? ucfirst($this->plan_type);
    }
}