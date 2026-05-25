<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignedTmas extends Model
{
     protected $fillable = [
        'user_id',
        'tma_question_id',
        'download_key',
        'expires_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tmaQuestion()
    {
        return $this->belongsTo(TmaQuestion::class);
    }


    
}