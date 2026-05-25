<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SemesterFee extends Model
{
    protected $fillable = ['semester_fee','level', 'semester', 'student_type'];
    protected $table = 'semester_fees';
    protected $casts = [
        'semester_fee' => 'decimal:2',
    ];
    
    
}