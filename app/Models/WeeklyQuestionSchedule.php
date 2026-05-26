<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeeklyQuestionSchedule extends Model
{
    protected $fillable = [
        'question_id',
        'week_number',
        'year',
        'scheduled_date',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function studentQuestionAttempts()
    {
        return $this->hasMany(StudentQuestionAttempt::class, 'weekly_question_schedule_id');
    }
}

