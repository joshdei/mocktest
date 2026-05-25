<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignDP extends Model
{
      protected $table = 'assign_d_p_s';

    protected $fillable = [
        'status',
        'course_id',
        'programme_id',
    ];
}