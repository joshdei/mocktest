<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
       protected $table = 'departments';

    // Allow mass assignment for department_name
    protected $fillable = ['department_name'];
    
}
