<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_Point extends Model
{
    protected $table = 'user__points'; // Specify the table name if it doesn't follow Laravel's convention

    protected $fillable = [
        'user_id',
        'bonus_points',
        'total_points',
        'used_points',
    ];
}

