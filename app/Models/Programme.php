<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
      protected $table = 'programmes';

    // Allow mass assignment for department_name
    protected $fillable = ['programmes_name'];

    // In Programme model



}