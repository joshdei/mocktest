<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentQuestionAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'weekly_question_schedule_id',
        'week_number',
        'year',
        'selected_option',
        'is_correct',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function weeklyQuestionSchedule()
    {
        return $this->belongsTo(WeeklyQuestionSchedule::class, 'weekly_question_schedule_id');
    }
}

