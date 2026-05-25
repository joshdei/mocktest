<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TmaQuestion extends Model
{
       protected $fillable = ['course_code', 'file_path'];

       public function assigned()
{
    return $this->hasMany(AssignedTmas::class);
}

}