<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    protected $table = 'timetables';

    // Allow mass assignment on these columns
    protected $fillable = [
        'exam_date',
        'type_of_time_table',
        'time_slot',
        'course_code',
        'course_title',
        'status',
    ];
}