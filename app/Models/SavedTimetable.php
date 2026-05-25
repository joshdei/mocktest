<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedTimetable extends Model
{
    protected $fillable = ['session_id', 'ip_address', 'name', 'level', 'subjects', 'timetable_data'];
    
    protected $casts = [
        'subjects' => 'array',
        'timetable_data' => 'array'
    ];
}