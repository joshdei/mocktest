<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MockpriceColor extends Model
{
    protected $fillable = [
        'plan_id',
        'bg_theme',
        'bg_color',
    ];
    public function plan()
    {
        return $this->belongsTo(MockPrice::class, 'plan_id', 'id');
    }
}
