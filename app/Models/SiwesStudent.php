<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiwesStudent extends Model
{
      protected $fillable = [
        'full_name',
        'pass_port',
        'matric_no',
        'year_of_study',
        'study_centre',
        'programme_id',
        'department_id',
        'level',
        'residential_address',
        'assumption_date',
        'attachment_start_date',
        'to_date',
      ];   
}
