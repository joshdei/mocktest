<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TmaCart extends Model
{     protected $fillable = ['user_id', 'material_id', 'course_code', 'seen'];

     public function user()
    {
        return $this->belongsTo(User::class);
    }
}