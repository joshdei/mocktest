<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    protected $fillable = [
        'user_id', 'course_id', 'plan_id', 'plan_name',
        'num_questions', 'duration', 'started_at', 'finished_at', 'score',
    ];

    protected $casts = [
        'started_at'  => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function user()   { return $this->belongsTo(User::class); }
    public function course() { return $this->belongsTo(Course::class); }
    public function plan()   { return $this->belongsTo(MockPrice::class, 'plan_id'); }
}