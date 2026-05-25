<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_Point2 extends Model
{
    protected $table = 'user__point2s';
        protected $fillable = [
        'user_id',
        'last_login_bonus_date',
    ];
}
