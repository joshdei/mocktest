<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DraftTimetable extends Model
{
    protected $fillable = [
        'exam_date',
        'type_of_time_table',
        'time_slot',
        'course_code',
        'course_title',
        'status', 
    ];
}
