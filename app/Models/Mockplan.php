<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mockplan extends Model
{
    protected $table = 'mock_plans';

    protected $fillable = [
        'userid',
        'plan_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }

    public function price()
    {
        return $this->belongsTo(MockPrice::class, 'plan_id');
    }

    // In Mockplan model:
public function mockPrice()
{
    return $this->belongsTo(MockPrice::class, 'plan_id');
}
}
