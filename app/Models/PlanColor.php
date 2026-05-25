<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanColor extends Model
{
    protected $table = 'mockprice_colors'; // Keep DB compatibility

    protected $fillable = [
        'plan_id',
        'bg_theme',
        'bg_color',
    ];

    // Relationship with Plan (formerly MockPrice)
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id');
    }
}
