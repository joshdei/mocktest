<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
   protected $fillable = [
    'course_id',
    'question',
    'option_a',
    'option_b',
    'option_c',
    'option_d',
    'answer',
    'question_type',
];


    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}