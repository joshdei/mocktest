<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'start_date',
        'expiry_date',
        'status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'expiry_date' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(MockPrice::class, 'plan_id');
    }
    /**
     * Plan detail helper for the current subscription.
     * This allows $userSubscription->planDetail to work directly.
     */
    public function planDetail()
    {
        return $this->hasOne(PlanDetail::class, 'plan_id', 'plan_id');
    }

    /**
     * Plan color helper for the current subscription.
     */
    public function planColor()
    {
        return $this->hasOne(PlanColor::class, 'plan_id', 'plan_id');
    }
    public function isActive()
    {
        return $this->status === 'active' && 
               (!$this->expiry_date || now()->lte($this->expiry_date));
    }
    
}
