<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PastQuestionPdf extends Model
{
    protected $fillable = ['course_code', 'file_path'];
}